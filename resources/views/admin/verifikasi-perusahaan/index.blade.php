@extends('layouts.app')
@section('title', 'Verifikasi Perusahaan')
@section('page-title', 'Verifikasi Perusahaan')
@section('breadcrumb')
    <li class="breadcrumb-item active">Verifikasi Perusahaan</li>
@endsection

@section('content')
<div class="row fade-in">
    <div class="col-12">
        
        <div class="card mb-4 border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-3">
                <ul class="nav nav-pills nav-fill flex-column flex-md-row">
                    <li class="nav-item">
                        <a class="nav-link {{ $status == 'pending' ? 'active' : '' }}" href="{{ route('admin.verifikasi-perusahaan.index', ['status' => 'pending']) }}">
                            <i class="fas fa-clock me-1"></i> Menunggu Verifikasi 
                            <span class="badge bg-light text-dark ms-2">{{ $counts['pending'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status == 'revision' ? 'active bg-warning text-dark' : '' }}" href="{{ route('admin.verifikasi-perusahaan.index', ['status' => 'revision']) }}">
                            <i class="fas fa-pencil-alt me-1"></i> Perlu Revisi
                            <span class="badge bg-light text-dark ms-2">{{ $counts['revision'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status == 'active' ? 'active bg-success' : '' }}" href="{{ route('admin.verifikasi-perusahaan.index', ['status' => 'active']) }}">
                            <i class="fas fa-check-circle me-1"></i> Disetujui
                            <span class="badge bg-light text-dark ms-2">{{ $counts['active'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status == 'rejected' ? 'active bg-danger' : '' }}" href="{{ route('admin.verifikasi-perusahaan.index', ['status' => 'rejected']) }}">
                            <i class="fas fa-times-circle me-1"></i> Ditolak
                            <span class="badge bg-light text-dark ms-2">{{ $counts['rejected'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status == 'all' ? 'active bg-secondary' : '' }}" href="{{ route('admin.verifikasi-perusahaan.index', ['status' => 'all']) }}">
                            <i class="fas fa-list me-1"></i> Semua Data
                            <span class="badge bg-light text-dark ms-2">{{ $counts['all'] }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card card-primary card-outline shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50" class="text-center">No</th>
                                <th>No. Pengajuan</th>
                                <th>Perusahaan</th>
                                <th>Dokumen PDF</th>
                                <th>Tanggal Daftar</th>
                                <th>Status</th>
                                <th width="100" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($companies as $i => $company)
                                <tr>
                                    <td class="text-center">{{ $companies->firstItem() + $i }}</td>
                                    <td>
                                        <div class="fw-bold text-primary">{{ $company->nomor_pengajuan ?: '-' }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($company->logo)
                                                <img src="{{ asset('storage/'.$company->logo) }}" class="rounded me-3 border" style="width: 40px; height: 40px; object-fit: contain;">
                                            @else
                                                <div class="rounded me-3 bg-light text-primary d-flex align-items-center justify-content-center fw-bold border" style="width: 40px; height: 40px;">
                                                    {{ strtoupper(substr($company->nama_perusahaan, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <strong class="text-dark">{{ $company->nama_perusahaan }}</strong><br>
                                                <span class="small text-muted">{{ $company->nama_penanggung_jawab }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($company->file_dokumen)
                                            <a href="{{ asset('storage/'.$company->file_dokumen) }}" target="_blank" class="btn btn-sm btn-outline-danger rounded-pill">
                                                <i class="fas fa-file-pdf me-1"></i> Lihat PDF
                                            </a>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $company->tanggal ? $company->tanggal->format('d/m/Y') : $company->created_at->format('d/m/Y') }}</div>
                                        <div class="small text-muted">{{ $company->created_at->format('H:i') }}</div>
                                    </td>
                                    <td>
                                        @if($company->isPending())
                                            <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Menunggu</span>
                                        @elseif($company->isRevision())
                                            <span class="badge bg-warning text-dark"><i class="fas fa-pencil-alt me-1"></i> Revisi</span>
                                        @elseif($company->isActive())
                                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Disetujui</span>
                                        @else
                                            <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.verifikasi-perusahaan.show', $company->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                                        <p class="mb-0">Belum ada data pengajuan dokumen.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($companies->hasPages())
                <div class="card-footer bg-white border-top-0 pt-3">
                    {{ $companies->links() }}
                </div>
            @endif
        </div>
        
    </div>
</div>
@endsection
