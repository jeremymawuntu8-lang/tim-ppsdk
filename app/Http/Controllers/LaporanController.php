<?php

namespace App\Http\Controllers;

use App\Models\BaWasAlse;
use App\Models\BaWasPrl;
use App\Models\JenisUsaha;
use App\Models\PelakuUsaha;
use App\Models\Provinsi;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $provinsis = Provinsi::orderBy('nama')->get();
        $jenisUsahas = JenisUsaha::orderBy('nama')->get();

        return view('laporan.index', compact('provinsis', 'jenisUsahas'));
    }

    protected function buildQuery(Request $request)
    {
        return PelakuUsaha::with(['jenisUsaha', 'provinsi', 'kabupaten', 'baWasPrls', 'baWasAlses'])
            ->when($request->provinsi_id, fn ($q, $v) => $q->where('provinsi_id', $v))
            ->when($request->kabupaten_id, fn ($q, $v) => $q->where('kabupaten_id', $v))
            ->when($request->jenis_usaha_id, fn ($q, $v) => $q->where('jenis_usaha_id', $v))
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when($request->dari_tanggal, fn ($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($request->sampai_tanggal, fn ($q, $v) => $q->whereDate('created_at', '<=', $v));
    }

    public function exportExcel(Request $request)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\PelakuUsahaExport($request->all()),
            'laporan-pengawasan-'.now()->format('Ymd-His').'.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $data = $this->buildQuery($request)->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.export-pdf', compact('data'))->setPaper('a4', 'landscape');

        return $pdf->download('laporan-pengawasan-'.now()->format('Ymd-His').'.pdf');
    }
}
