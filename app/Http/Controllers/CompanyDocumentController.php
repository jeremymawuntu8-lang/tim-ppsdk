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
        $request->validate([
            'judul' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:1000',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:5120'
        ]);

        $company = auth()->user()->company;
        
        $file = $request->file('file');
        $namaFile = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('company_documents/' . $company->id, $namaFile, 'public');

        $company->documents()->create([
            'judul' => $request->judul,
            'keterangan' => $request->keterangan,
            'nama_file' => $namaFile,
            'path_file' => $path
        ]);

        return back()->with('success', 'Dokumen berhasil diunggah.');
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

        if (!Storage::disk('public')->exists($document->path_file)) {
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

        if (Storage::disk('public')->exists($document->path_file)) {
            Storage::disk('public')->delete($document->path_file);
        }

        $document->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }
}
