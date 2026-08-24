@extends('layouts.app')
@section('title', 'Jenis Usaha')
@section('page-title', 'Master Jenis Usaha')
@section('breadcrumb')
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item active">Jenis Usaha</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline fade-in">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0"><i class="fas fa-list me-2"></i>Daftar Jenis Usaha</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabelJenisUsaha" class="table table-striped table-hover w-100">
                        <thead>
                            <tr>
                                <th width="40">No</th>
                                <th>Nama Jenis Usaha</th>
                                <th>Kode KBLI</th>
                                <th width="100">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let table = $('#tabelJenisUsaha').DataTable({
        processing: true, serverSide: true, responsive: true,
        ajax: "{{ route('jenis-usaha.data') }}",
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'nama' },
            { data: 'kode_kbli', defaultContent: '-' },
            { data: 'aksi', orderable: false, searchable: false, className: 'text-center' },
        ]
    });

    function hapusJenisUsaha(id) {
        confirmDelete(`/jenis-usaha/${id}`, () => table.draw());
    }
</script>
@endpush
