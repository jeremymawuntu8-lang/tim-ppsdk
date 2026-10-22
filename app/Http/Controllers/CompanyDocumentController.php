<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyDocument;
use Illuminate\Support\Facades\Storage;
use App\Models\ActivityLog;

class CompanyDocumentController extends Controller
{
    public function index()
    {
        $company = auth()->user()->company;
        $documents = $company->documents()->latest()->get();
        return view('company.upload', compact('company', 'documents'));
    }

    public function store(Request $request)
    {
        $tipe = $request->input('tipe', 'file');
        $linkDrive = trim((string) $request->input('link_drive'));
        if ($linkDrive && !preg_match('~^(?:f|ht)tps?://~i', $linkDrive)) {
            $linkDrive = 'https://' . $linkDrive;
        }

        $company = auth()->user()->company;

        if ($tipe === 'link' || (!$request->hasFile('file') && !empty($linkDrive))) {
            $request->validate([
                'judul' => 'required|string|max:255',
                'keterangan' => 'nullable|string|max:1000',
                'link_drive' => 'required|url|max:2048',
            ]);

            $company->documents()->create([
                'tipe' => 'link',
                'judul' => $request->judul,
                'keterangan' => $request->keterangan,
                'nama_file' => 'Tautan Google Drive',
                'path_file' => null,
                'link_drive' => $linkDrive,
            ]);
        } else {
            $request->validate([
                'judul' => 'required|string|max:255',
                'keterangan' => 'nullable|string|max:1000',
                'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:' . config('upload.max_file_size_kb')
            ]);

            $file = $request->file('file');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('company_documents/' . $company->id, $namaFile, 'public');

            $company->documents()->create([
                'tipe' => 'file',
                'judul' => $request->judul,
                'keterangan' => $request->keterangan,
                'nama_file' => $namaFile,
                'path_file' => $path,
                'link_drive' => null,
            ]);
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Upload Dokumen Lanjutan',
            'description' => 'Perusahaan ' . $company->nama_perusahaan . ' mengunggah dokumen lanjutan: ' . $request->judul,
            'ip_address' => $request->ip()
        ]);

        return back()->with('success', 'Dokumen / Tautan berhasil disimpan.');
    }

    public function download(CompanyDocument $document)
    {
        // Ensure user owns this document or is admin
        $user = auth()->user();
        if (!$user->hasAnyRole(['super-admin', 'admin', 'pengawas', 'pimpinan'])) {
            if ($user->company->id !== $document->company_id) {
                abort(403);
            }
        }

        if ($document->isLink()) {
            return redirect()->away($document->link_drive);
        }

        if (!$document->path_file || !Storage::disk('public')->exists($document->path_file)) {
            abort(404, 'File fisik arsip tidak ditemukan di server.');
        }

        return Storage::disk('public')->download($document->path_file, $document->nama_file);
    }

    public function destroy(CompanyDocument $document)
    {
        $user = auth()->user();
        if ($user->company->id !== $document->company_id) {
            abort(403);
        }

        if ($document->path_file && Storage::disk('public')->exists($document->path_file)) {
            Storage::disk('public')->delete($document->path_file);
        }

        $document->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }
}
