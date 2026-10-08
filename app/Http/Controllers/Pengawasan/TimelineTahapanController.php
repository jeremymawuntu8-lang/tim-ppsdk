<?php

namespace App\Http\Controllers\Pengawasan;

use App\Http\Controllers\Controller;
use App\Models\PelakuUsaha;
use App\Models\TimelineTahapan;
use App\Models\TimelineTahapanFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TimelineTahapanController extends Controller
{
    public function upload(Request $request, PelakuUsaha $pelakuUsaha)
    {
        $request->validate([
            'tahap' => 'required|string',
            'nama_dokumen' => 'required|string',
            'file' => 'required|file|max:10240',
        ]);

        $tahapan = $pelakuUsaha->timelineTahapans()->firstOrCreate(
            ['tahap' => $request->tahap],
            ['status' => 'proses']
        );

        $path = $request->file('file')->store('timeline_files', 'public');

        $tahapan->files()->create([
            'nama_dokumen' => $request->nama_dokumen,
            'file_path' => $path,
        ]);

        if ($request->action_type === 'submit') {
            $tahapan->update([
                'status' => 'selesai',
                'tanggal' => now()
            ]);
            return redirect()->route('timeline-pengawasan.show', $pelakuUsaha->id)->with('success', 'Dokumen berhasil diunggah dan tahap ditandai selesai');
        }

        return back()->with('success', 'Dokumen berhasil diunggah');
    }

    public function updateStatus(Request $request, PelakuUsaha $pelakuUsaha)
    {
        $request->validate([
            'tahap' => 'required|string',
            'status' => 'required|string',
        ]);

        $tahapan = $pelakuUsaha->timelineTahapans()->firstOrCreate(
            ['tahap' => $request->tahap],
            ['status' => $request->status]
        );

        $tahapan->update([
            'status' => $request->status,
            'tanggal' => now(),
        ]);

        return back()->with('success', 'Status tahap berhasil diperbarui');
    }

    public function deleteFile(TimelineTahapanFile $file)
    {
        Storage::disk('public')->delete($file->file_path);
        $file->delete();

        return back()->with('success', 'Dokumen berhasil dihapus');
    }
}
