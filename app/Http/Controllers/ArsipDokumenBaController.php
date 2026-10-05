<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ArsipDokumenBa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArsipDokumenBaController extends Controller
{
    /**
     * Mapping tipe BA ke model class.
     */
    public const BA_MODELS = [
        'ba-was-prl'       => \App\Models\BaWasPrl::class,
        'ba-was-alse'      => \App\Models\BaWasAlse::class,
        'ba-reklamasi'     => \App\Models\BaReklamasi::class,
        'ba-ppk'           => \App\Models\BaPpk::class,
        'ba-pencemaran'    => \App\Models\BaPencemaran::class,
        'surat-peringatan' => \App\Models\SuratPeringatan::class,
    ];

    public const BA_LABELS = [
        'ba-was-prl'       => 'BA WAS PRL',
        'ba-was-alse'      => 'BA WAS ALSE',
        'ba-reklamasi'     => 'BA Reklamasi',
        'ba-ppk'           => 'BA PPK',
        'ba-pencemaran'    => 'BA Pencemaran',
        'surat-peringatan' => 'Surat Peringatan',
    ];

    /**
     * Halaman Utama Menu Arsip Dokumen BA.
     * Dapat diakses oleh admin, pengawas, pimpinan, dan staf pengawasan.
     */
    public function index(Request $request)
    {
        $query = ArsipDokumenBa::with(['arsipable', 'uploader'])
            ->where('arsipable_type', '!=', \App\Models\SuratPeringatan::class)
            ->latest();

        if ($request->filled('tipe_ba') && isset(self::BA_MODELS[$request->tipe_ba])) {
            $query->where('arsipable_type', self::BA_MODELS[$request->tipe_ba]);
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('judul', 'like', "%{$q}%")
                    ->orWhere('nama_file', 'like', "%{$q}%")
                    ->orWhere('keterangan', 'like', "%{$q}%");
            });
        }

        $arsipList = $query->paginate(15)->withQueryString();

        $stats = [
            'total'  => ArsipDokumenBa::count(),
            'files'  => ArsipDokumenBa::where('tipe', 'file')->count(),
            'links'  => ArsipDokumenBa::where('tipe', 'link')->count(),
        ];

        // List BA untuk pilihan modal tambah
        $baList = [
            'ba-was-prl'       => \App\Models\BaWasPrl::select('id', 'nomor_ba')->latest()->get(),
            'ba-was-alse'      => \App\Models\BaWasAlse::select('id', 'nomor_ba')->latest()->get(),
            'ba-reklamasi'     => \App\Models\BaReklamasi::select('id', 'nomor_ba')->latest()->get(),
            'ba-ppk'           => \App\Models\BaPpk::select('id', 'nomor_ba')->latest()->get(),
            'ba-pencemaran'    => \App\Models\BaPencemaran::select('id', 'nomor_ba')->latest()->get(),
        ];

        return view('arsip-dokumen-ba.index', compact('arsipList', 'stats', 'baList'));
    }

    /**
     * Halaman Utama Menu Arsip Dokumen Surat Peringatan.
     */
    public function indexSp(Request $request)
    {
        $query = ArsipDokumenBa::with(['arsipable', 'uploader'])
            ->where('arsipable_type', \App\Models\SuratPeringatan::class)
            ->latest();

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('judul', 'like', "%{$q}%")
                    ->orWhere('nama_file', 'like', "%{$q}%")
                    ->orWhere('keterangan', 'like', "%{$q}%");
            });
        }

        $arsipList = $query->paginate(15)->withQueryString();

        $stats = [
            'total'  => ArsipDokumenBa::where('arsipable_type', \App\Models\SuratPeringatan::class)->count(),
            'files'  => ArsipDokumenBa::where('arsipable_type', \App\Models\SuratPeringatan::class)->where('tipe', 'file')->count(),
            'links'  => ArsipDokumenBa::where('arsipable_type', \App\Models\SuratPeringatan::class)->where('tipe', 'link')->count(),
        ];

        $spList = \App\Models\SuratPeringatan::select('id', 'id_penerbitan as nomor_ba')->latest()->get();

        return view('surat-peringatan.arsip', compact('arsipList', 'stats', 'spList'));
    }

    /**
     * Simpan arsip dokumen lama (file upload atau link Google Drive).
     * Khusus Admin / Super-Admin.
     */
    public function store(Request $request)
    {
        abort_unless(
            auth()->user()->hasAnyRole(['super-admin', 'admin']),
            403,
            'Hanya Administrator yang memiliki akses untuk mengunggah arsip dokumen.'
        );

        $data = $request->validate([
            'arsipable_type' => ['required', 'string', 'in:' . implode(',', array_keys(self::BA_MODELS))],
            'arsipable_id'   => ['required', 'integer'],
            'tipe'           => ['required', 'in:file,link'],
            'judul'          => ['nullable', 'string', 'max:255'],
            'keterangan'     => ['nullable', 'string', 'max:1000'],
            'file_arsip'     => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,zip,rar', 'max:102400'],
            'files_arsip'    => ['nullable', 'array'],
            'files_arsip.*'  => ['file', 'mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,zip,rar', 'max:102400'],
            'link_gdrive'    => ['required_if:tipe,link', 'nullable', 'string', 'max:2048'],
        ]);

        $modelClass = self::BA_MODELS[$data['arsipable_type']];
        $ba = $modelClass::findOrFail($data['arsipable_id']);

        if ($data['tipe'] === 'link') {
            $rawLink = trim($data['link_gdrive']);
            if (!preg_match('~^(?:f|ht)tps?://~i', $rawLink)) {
                $rawLink = 'https://' . $rawLink;
            }

            $arsip = new ArsipDokumenBa();
            $arsip->arsipable_type = $modelClass;
            $arsip->arsipable_id = $ba->id;
            $arsip->tipe = 'link';
            $arsip->judul = !empty($data['judul']) ? $data['judul'] : 'Folder Arsip Dokumen Google Drive';
            $arsip->keterangan = $data['keterangan'] ?? null;
            $arsip->link_gdrive = $rawLink;
            $arsip->uploaded_by = auth()->id();
            $arsip->save();

            ActivityLog::catat('Tambah', 'Arsip Dokumen BA', "Menambahkan link Google Drive arsip: {$arsip->judul}");

            return back()->with('success', 'Link Google Drive arsip berhasil ditambahkan.');
        }

        // Tipe File
        $filesToSave = [];
        if ($request->hasFile('files_arsip')) {
            $filesToSave = $request->file('files_arsip');
        } elseif ($request->hasFile('file_arsip')) {
            $filesToSave = [$request->file('file_arsip')];
        }

        if (empty($filesToSave)) {
            return back()->withErrors(['file_arsip' => 'Pilih setidaknya satu file dokumen untuk diunggah.'])->withInput();
        }

        $count = count($filesToSave);
        foreach ($filesToSave as $index => $file) {
            $arsip = new ArsipDokumenBa();
            $arsip->arsipable_type = $modelClass;
            $arsip->arsipable_id = $ba->id;
            $arsip->tipe = 'file';

            if (!empty($data['judul'])) {
                $arsip->judul = $count > 1 ? "{$data['judul']} (" . ($index + 1) . ")" : $data['judul'];
            } else {
                $arsip->judul = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            }

            $arsip->keterangan = $data['keterangan'] ?? null;
            $arsip->nama_file = $file->getClientOriginalName();
            $arsip->path_file = $file->store('arsip-dokumen-ba', 'public');
            $arsip->uploaded_by = auth()->id();
            $arsip->save();

            ActivityLog::catat('Upload', 'Arsip Dokumen BA', "Mengunggah arsip dokumen: {$arsip->nama_file}");
        }

        $pesan = $count > 1
            ? "{$count} berkas arsip dokumen berhasil diunggah."
            : "Berkas arsip dokumen berhasil diunggah.";

        return back()->with('success', $pesan);
    }

    /**
     * Download atau lihat file arsip.
     * Dapat diakses oleh pengawas, petugas, pimpinan, admin.
     */
    public function download(ArsipDokumenBa $arsipDokumenBa)
    {
        abort_unless(
            auth()->user()->can('kelola-pengawasan') ||
            auth()->user()->can('lihat-laporan') ||
            auth()->user()->hasAnyRole(['super-admin', 'admin', 'pengawas', 'pimpinan']),
            403,
            'Akses tidak diizinkan.'
        );

        if (!$arsipDokumenBa->isFile() || !$arsipDokumenBa->path_file) {
            abort(404, 'File arsip tidak ditemukan.');
        }

        if (!Storage::disk('public')->exists($arsipDokumenBa->path_file)) {
            abort(404, 'File fisik arsip tidak ditemukan di server.');
        }

        ActivityLog::catat('Unduh', 'Arsip Dokumen BA', "Melihat/mengunduh arsip dokumen: {$arsipDokumenBa->judul}");

        $fullPath = Storage::disk('public')->path($arsipDokumenBa->path_file);

        // Jika minta inline preview (buka di tab)
        if (request()->query('preview') || request()->query('view')) {
            return response()->file($fullPath, [
                'Content-Disposition' => 'inline; filename="' . addslashes($arsipDokumenBa->nama_file) . '"'
            ]);
        }

        return Storage::disk('public')->download($arsipDokumenBa->path_file, $arsipDokumenBa->nama_file);
    }

    /**
     * Hapus arsip dokumen (hanya admin/super-admin).
     */
    public function destroy(ArsipDokumenBa $arsipDokumenBa)
    {
        abort_unless(
            auth()->user()->hasAnyRole(['super-admin', 'admin']),
            403,
            'Hanya Administrator yang memiliki akses untuk menghapus arsip dokumen.'
        );

        if ($arsipDokumenBa->isFile() && $arsipDokumenBa->path_file) {
            Storage::disk('public')->delete($arsipDokumenBa->path_file);
        }

        $judul = $arsipDokumenBa->judul;
        ActivityLog::catat('Hapus', 'Arsip Dokumen BA', "Menghapus arsip dokumen: {$judul}");

        $arsipDokumenBa->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => "Arsip '{$judul}' berhasil dihapus."]);
        }

        return back()->with('success', "Arsip dokumen '{$judul}' berhasil dihapus.");
    }
}
