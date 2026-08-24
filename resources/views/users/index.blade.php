@extends('layouts.app')
@section('title', 'User Management')
@section('page-title', 'User Management')
@section('breadcrumb')<li class="breadcrumb-item active">User Management</li>@endsection
@section('content')
<div class="card card-primary card-outline fade-in">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h3 class="card-title mb-0"><i class="fas fa-users-gear me-2"></i>Daftar Pengguna</h3>
        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Tambah User</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tabelUsers" class="table table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th width="40">No</th>
                        <th>Nama</th>
                        <th class="d-none d-md-table-cell">Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
let table = $('#tabelUsers').DataTable({
    processing: true, serverSide: true, responsive: true,
    ajax: "{{ route('users.data') }}",
    columns: [
        { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
        { data: 'name' },
        { data: 'email', className: 'd-none d-md-table-cell' },
        { data: 'role' },
        { data: 'status_badge', orderable: false, className: 'text-center' },
        { data: 'aksi', orderable: false, searchable: false, className: 'text-center' },
    ]
});

function hapusUser(id) {
    confirmDelete(`/users/${id}`, () => table.draw());
}
</script>
@endpush
