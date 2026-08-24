@extends('layouts.app')
@section('title', 'BA WAS PRL')
@section('page-title', 'BA WAS PRL')
@section('breadcrumb')<li class="breadcrumb-item">Pengawasan</li><li class="breadcrumb-item active">BA WAS PRL</li>@endsection
@section('content')
<div class="card card-primary card-outline fade-in">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h3 class="card-title mb-0"><i class="fas fa-file-lines me-2"></i>Daftar Berita Acara Pengawasan PRL</h3>
        <a href="{{ route('ba-was-prl.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Buat BA</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tabelBaPrl" class="table table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th width="40">No</th>
                        <th>Nomor BA</th>
                        <th>Perusahaan</th>
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
    let table = $('#tabelBaPrl').DataTable({
        processing: true, serverSide: true, responsive: true,
        ajax: "{{ route('ba-was-prl.data') }}",
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
