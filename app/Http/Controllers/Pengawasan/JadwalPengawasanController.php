<?php

namespace App\Http\Controllers\Pengawasan;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\JadwalPengawasan;
use App\Http\Requests\JadwalPengawasanRequest;
use App\Models\PelakuUsaha;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class JadwalPengawasanController extends Controller
{
    public function index()
    {
        $pelakuUsahas = PelakuUsaha::orderBy('nama_perusahaan')->get();
        return view('jadwal.index', compact('pelakuUsahas'));
    }

    public function data(Request $request)
    {
        $query = JadwalPengawasan::with('pelakuUsaha')->when($request->status, fn ($q, $v) => $q->where('status', $v));

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('perusahaan', fn ($r) => $r->pelakuUsaha->nama_perusahaan ?? '-')
            ->addColumn('tanggal', fn ($r) => $r->tanggal_rencana?->format('d/m/Y'))
            ->addColumn('status_badge', fn ($r) => '<span class="badge bg-'.match ($r->status) {
                'selesai' => 'success', 'sedang_berjalan' => 'warning', 'dibatalkan' => 'danger', default => 'secondary',
            }.'">'.ucwords(str_replace('_', ' ', $r->status)).'</span>')
            ->addColumn('aksi', fn ($r) => view('jadwal.partials.aksi', ['row' => $r])->render())
            ->rawColumns(['status_badge', 'aksi'])
            ->make(true);
    }

    public function store(JadwalPengawasanRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();

        JadwalPengawasan::create($data);
        ActivityLog::catat('Tambah', 'Jadwal Pengawasan', 'Menambahkan jadwal pengawasan baru');

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(JadwalPengawasan $jadwal)
    {
        $pelakuUsahas = PelakuUsaha::orderBy('nama_perusahaan')->get();
        return view('jadwal.edit', compact('jadwal', 'pelakuUsahas'));
    }

    public function update(JadwalPengawasanRequest $request, JadwalPengawasan $jadwal)
    {
        $jadwal->update($request->validated());
        ActivityLog::catat('Edit', 'Jadwal Pengawasan', 'Mengubah jadwal pengawasan');

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(JadwalPengawasan $jadwal)
    {
        ActivityLog::catat('Hapus', 'Jadwal Pengawasan', 'Menghapus jadwal pengawasan');
        $jadwal->delete();

        return response()->json(['success' => true, 'message' => 'Jadwal berhasil dihapus.']);
    }
}
