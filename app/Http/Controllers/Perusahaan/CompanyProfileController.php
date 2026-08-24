<?php

namespace App\Http\Controllers\Perusahaan;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyProfileController extends Controller
{
    /**
     * Tampilkan form "Upload Dokumen Perizinan" (pertama kali login).
     */
    public function create()
    {
        $company = auth()->user()->company;
        
        // Jika sudah punya profil dan BUKAN sedang dalam status revision, redirect ke dashboard
        if ($company && !$company->isRevision()) {
            return redirect()->route('company.dashboard');
        }

        // Jika sedang revisi, kita bisa arahkan ke halaman edit
        if ($company && $company->isRevision()) {
            return redirect()->route('company.profil.edit');
        }

        return view('company.complete-profile');
    }

    /**
     * Simpan dokumen yang diupload (status: pending).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_perusahaan'          => ['required', 'string', 'max:255'],
            'tanggal'                  => ['required', 'date'],
            'nama_penanggung_jawab'    => ['required', 'string', 'max:255'],
            'jabatan_penanggung_jawab' => ['required', 'string', 'max:255'],
            'nomor_telepon'            => ['required', 'string', 'max:30'],
            'dokumen_diunggah'         => ['required', 'string'],
            'keterangan_tambahan'      => ['nullable', 'string'],
            'file_dokumen'             => ['required', 'file', 'mimes:pdf', 'max:1048576'], // max 1GB
        ]);

        if ($request->hasFile('file_dokumen')) {
            $data['file_dokumen'] = $request->file('file_dokumen')->store('dokumen-perizinan', 'public');
        }

        $data['user_id'] = auth()->id();
        $data['status']  = 'pending';
        $data['email_perusahaan'] = auth()->user()->email;
        $data['nomor_pengajuan'] = 'DOC-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        $company = Company::create($data);

        return redirect()->route('company.upload.success')->with('company_id', $company->id);
    }

    /**
     * Halaman Sukses Upload
     */
    public function success()
    {
        $companyId = session('company_id');
        
        if (!$companyId) {
            return redirect()->route('company.dashboard');
        }

        $company = Company::findOrFail($companyId);
        
        // Cek agar user lain tidak bisa melihat dokumen yang bukan miliknya
        if ($company->user_id !== auth()->id()) {
            abort(403);
        }

        return view('company.upload-success', compact('company'));
    }

    /**
     * Tampilkan form edit profil (dihilangkan sementara atau dibiarkan saja)
     */
    public function edit()
    {
        $company = auth()->user()->company;
        if (! $company) {
            return redirect()->route('company.complete-profile');
        }
        return view('company.edit-profile', compact('company'));
    }

    /**
     * Update profil perusahaan (dibiarkan sementara).
     */
    public function update(Request $request)
    {
        $company = auth()->user()->company;
        
        if (!$company) {
            return redirect()->route('company.complete-profile');
        }

        $data = $request->validate([
            'nama_perusahaan'          => ['required', 'string', 'max:255'],
            'tanggal'                  => ['required', 'date'],
            'nama_penanggung_jawab'    => ['required', 'string', 'max:255'],
            'jabatan_penanggung_jawab' => ['required', 'string', 'max:255'],
            'nomor_telepon'            => ['required', 'string', 'max:30'],
            'dokumen_diunggah'         => ['required', 'string'],
            'keterangan_tambahan'      => ['nullable', 'string'],
            'file_dokumen'             => ['nullable', 'file', 'mimes:pdf', 'max:1048576'], // optional saat edit
        ]);

        if ($request->hasFile('file_dokumen')) {
            // Hapus file lama jika ada
            if ($company->file_dokumen && Storage::disk('public')->exists($company->file_dokumen)) {
                Storage::disk('public')->delete($company->file_dokumen);
            }
            $data['file_dokumen'] = $request->file('file_dokumen')->store('dokumen-perizinan', 'public');
        }

        // Kembalikan status ke pending agar admin bisa verifikasi ulang
        $data['status'] = 'pending';
        $data['catatan_admin'] = null; // Hapus catatan revisi sebelumnya
        $data['verified_by'] = null;
        $data['verified_at'] = null;

        $company->update($data);

        return redirect()->route('company.upload.success')->with('company_id', $company->id);
    }
}
