@extends('layouts.app')
@section('title', 'Data Provinsi')
@section('page-title', 'Wilayah - Provinsi')
@section('breadcrumb')
    <li class="breadcrumb-item">Master Data</li><li class="breadcrumb-item active">Wilayah</li>
@endsection
@section('content')
<div class="card card-primary card-outline fade-in">
    <div class="card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs nav-justified" style="border-top-left-radius: .25rem; border-top-right-radius: .25rem;">
            <li class="nav-item"><a class="nav-link fw-semibold {{ request()->routeIs('wilayah.provinsi') ? 'active bg-white border-bottom-0 text-primary' : 'bg-light text-muted' }}" href="{{ route('wilayah.provinsi') }}"><i class="fas fa-map me-1 d-none d-md-inline"></i> Provinsi</a></li>
            <li class="nav-item"><a class="nav-link fw-semibold {{ request()->routeIs('wilayah.kabupaten') ? 'active bg-white border-bottom-0 text-primary' : 'bg-light text-muted' }}" href="{{ route('wilayah.kabupaten') }}"><i class="fas fa-map-marked me-1 d-none d-md-inline"></i> Kabupaten</a></li>
            <li class="nav-item"><a class="nav-link fw-semibold {{ request()->routeIs('wilayah.kecamatan') ? 'active bg-white border-bottom-0 text-primary' : 'bg-light text-muted' }}" href="{{ route('wilayah.kecamatan') }}"><i class="fas fa-map-signs me-1 d-none d-md-inline"></i> Kecamatan</a></li>
            <li class="nav-item"><a class="nav-link fw-semibold {{ request()->routeIs('wilayah.kelurahan') ? 'active bg-white border-bottom-0 text-primary' : 'bg-light text-muted' }}" href="{{ route('wilayah.kelurahan') }}"><i class="fas fa-map-pin me-1 d-none d-md-inline"></i> Kelurahan</a></li>
        </ul>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0">
                <thead class="table-light"><tr><th width="50">No</th><th>Kode Wilayah</th><th>Nama Provinsi</th></tr></thead>
                <tbody>
                    @forelse($provinsis as $i => $item)
                    <tr>
                        <td>{{ $provinsis->firstItem() + $i }}</td>
                        <td><span class="badge bg-secondary font-monospace">{{ $item->kode }}</span></td>
                        <td class="fw-bold">{{ $item->nama }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center text-muted py-5"><i class="fas fa-folder-open fa-2x mb-2 opacity-50"></i><br>Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end mt-4">
            {{ $provinsis->links() }}
        </div>
    </div>
</div>
@endsection
