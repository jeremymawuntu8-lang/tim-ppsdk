@extends('layouts.app')
@section('title', 'Log Aktivitas')
@section('page-title', 'Log Aktivitas Sistem')
@section('breadcrumb')<li class="breadcrumb-item active">Log Aktivitas</li>@endsection
@section('content')
<div class="card card-primary card-outline fade-in">
    <div class="card-header">
        <h3 class="card-title mb-0"><i class="fas fa-clock-rotate-left me-2"></i>Riwayat Aktivitas Pengguna</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tabelLog" class="table table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th width="40">No</th>
                        <th>Waktu</th>
                        <th>User</th>
                        <th class="d-none d-md-table-cell">Aktivitas</th>
                        <th class="d-none d-sm-table-cell">Modul</th>
                        <th class="d-none d-lg-table-cell">Deskripsi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
let table = $('#tabelLog').DataTable({
    processing: true, serverSide: true, responsive: true,
    order: [[1, 'desc']], // Urutkan berdasarkan waktu terbaru
    ajax: "{{ route('log-aktivitas.data') }}",
    columns: [
        { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
        { data: 'waktu', name: 'created_at' },
        { data: 'user' },
        { data: 'aktivitas_badge', name: 'aktivitas', className: 'd-none d-md-table-cell text-center' },
        { data: 'modul', className: 'd-none d-sm-table-cell text-center' },
        { data: 'deskripsi', className: 'd-none d-lg-table-cell' },
    ]
});
</script>
@endpush
