@extends('layouts.app')
@section('title', 'BA PPK')
@section('page-title', 'BA PPK')
@section('breadcrumb')<li class="breadcrumb-item">Pengawasan</li><li class="breadcrumb-item active">BA PPK</li>@endsection
@section('content')
<div class="card card-primary card-outline fade-in">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h3 class="card-title mb-0"><i class="fas fa-file-lines me-2"></i>Daftar BA Pengawasan PPK</h3>
        <a href="{{ route('ba-ppk.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Buat BA</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tabelBaPpk" class="table table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th width="40">No</th>
                        <th>Nomor BA</th>
                        <th>Pelaku Usaha / Kegiatan</th>
                        <th class="d-none d-md-table-cell">Tanggal</th>
                        <th>Status</th>
                        <th width="140">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    let table = $('#tabelBaPpk').DataTable({
        processing: true, serverSide: true, responsive: true,
        ajax: "{{ route('ba-ppk.data') }}",
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'nomor_ba' },
            { data: 'perusahaan' },
            { data: 'tanggal', className: 'd-none d-md-table-cell' },
            { data: 'status_badge', orderable: false, className: 'text-center' },
            { data: 'aksi', orderable: false, searchable: false, className: 'text-center' },
        ]
    });
</script>
@endpush
