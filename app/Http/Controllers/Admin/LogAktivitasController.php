<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LogAktivitasController extends Controller
{
    public function index()
    {
        return view('log-aktivitas.index');
    }

    public function data(Request $request)
    {
        $query = ActivityLog::with('user')
            ->when($request->aktivitas, fn ($q, $v) => $q->where('aktivitas', $v))
            ->when($request->dari_tanggal, fn ($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($request->sampai_tanggal, fn ($q, $v) => $q->whereDate('created_at', '<=', $v))
            ->latest();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('user', fn ($r) => $r->user->name ?? 'Sistem')
            ->addColumn('waktu', fn ($r) => $r->created_at->format('d/m/Y H:i:s'))
            ->addColumn('aktivitas_badge', function ($r) {
                $color = match(strtolower($r->aktivitas)) {
                    'tambah', 'approve' => 'success',
                    'edit', 'update', 'revision' => 'warning text-dark',
                    'hapus', 'reject' => 'danger',
                    default => 'primary',
                };
                return '<span class="badge bg-'.$color.'">'.strtoupper($r->aktivitas).'</span>';
            })
            ->rawColumns(['aktivitas_badge'])
            ->make(true);
    }
}
