<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Company;
use App\Notifications\CompanyStatusNotification;
use Illuminate\Http\Request;

class VerifikasiPerusahaanController extends Controller
{
    /**
     * Daftar semua perusahaan yang mendaftar.
     */
    public function index(Request $request)
    {
        $status    = $request->get('status', 'pending');
        $companies = Company::with('user')
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $counts = [
            'pending'  => Company::pending()->count(),
            'revision' => Company::revision()->count(),
            'active'   => Company::active()->count(),
            'rejected' => Company::rejected()->count(),
            'all'      => Company::count(),
        ];

        return view('admin.verifikasi-perusahaan.index', compact('companies', 'status', 'counts'));
    }

    /**
     * Detail perusahaan.
     */
    public function show(Company $company)
    {
        $company->load('user', 'verifiedBy');
        return view('admin.verifikasi-perusahaan.show', compact('company'));
    }

    /**
     * Approve perusahaan.
     */
    public function approve(Request $request, Company $company)
    {
        try {
            $company->update([
                'status'           => 'active',
                'verified_by'      => auth()->id(),
                'verified_at'      => now(),
                'rejection_reason' => null,
                'catatan_admin'    => $request->filled('catatan_admin') ? $request->catatan_admin : null,
            ]);

            // Kirim notifikasi email ke perusahaan
            try {
                $company->user->notify(new CompanyStatusNotification($company));
            } catch (\Exception $e) {
                \Log::warning("Gagal mengirim email notifikasi approve: " . $e->getMessage());
            }

            ActivityLog::catat('approve', 'VerifikasiPerusahaan', "Menyetujui dokumen perusahaan: {$company->nama_perusahaan}");

            return redirect()->route('admin.verifikasi-perusahaan.index')
                ->with('success', "Perusahaan \"{$company->nama_perusahaan}\" berhasil disetujui.");
        } catch (\Exception $e) {
            \Log::error("Gagal menyetujui perusahaan #{$company->id}: " . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan saat menyetujui perusahaan. Silakan coba lagi.');
        }
    }

    /**
     * Kembalikan untuk revisi.
     */
    public function revision(Request $request, Company $company)
    {
        $request->validate([
            'catatan_admin' => ['required', 'string', 'min:5'],
        ], [
            'catatan_admin.required' => 'Catatan revisi wajib diisi.',
            'catatan_admin.min'      => 'Catatan revisi minimal 5 karakter.',
        ]);

        try {
            $company->update([
                'status'        => 'revision',
                'catatan_admin' => $request->catatan_admin,
                'verified_by'   => auth()->id(),
                'verified_at'   => now(),
                'rejection_reason' => null,
            ]);

            // Kirim notifikasi email ke perusahaan
            try {
                $company->user->notify(new CompanyStatusNotification($company));
            } catch (\Exception $e) {
                \Log::warning("Gagal mengirim email notifikasi revision: " . $e->getMessage());
            }

            ActivityLog::catat('revision', 'VerifikasiPerusahaan', "Meminta revisi dokumen perusahaan: {$company->nama_perusahaan}");

            return redirect()->route('admin.verifikasi-perusahaan.index')
                ->with('success', "Permintaan revisi untuk \"{$company->nama_perusahaan}\" telah dikirim.");
        } catch (\Exception $e) {
            \Log::error("Gagal mengirim revisi perusahaan #{$company->id}: " . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan saat mengirim permintaan revisi. Silakan coba lagi.');
        }
    }

    /**
     * Reject perusahaan.
     */
    public function reject(Request $request, Company $company)
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
            'rejection_reason.min'      => 'Alasan penolakan minimal 10 karakter.',
        ]);

        $company->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'catatan_admin'    => null,
            'verified_by'      => auth()->id(),
            'verified_at'      => now(),
        ]);

        // Kirim notifikasi email ke perusahaan
        try {
            $company->user->notify(new CompanyStatusNotification($company));
        } catch (\Exception $e) {
            \Log::warning("Gagal mengirim email notifikasi reject: " . $e->getMessage());
        }

        ActivityLog::catat('reject', 'VerifikasiPerusahaan', "Menolak perusahaan: {$company->nama_perusahaan}");

        return redirect()->route('admin.verifikasi-perusahaan.index')
            ->with('success', "Perusahaan \"{$company->nama_perusahaan}\" telah ditolak.");
    }
}