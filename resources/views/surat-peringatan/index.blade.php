@extends('layouts.app')

@section('title', 'Data Surat Peringatan')

@section('content')
<div class="container-fluid">
    <div class="card card-outline card-primary">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Data Surat Peringatan</h3>
            <div class="ms-auto d-flex gap-2">
                <a href="{{ route('surat-peringatan.export') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
                <a href="{{ route('surat-peringatan.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Data
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="table-data" class="table table-bordered table-striped table-hover w-100">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>ID Penerbitan</th>
                            <th>Subjek Hukum / Perusahaan</th>
                            <th>No. KKPRL</th>
                            <th>Status Akhir (Sanksi)</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#table-data').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('surat-peringatan.data') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center'},
                {data: 'id_penerbitan', name: 'id_penerbitan'},
                {data: 'subjek_hukum', name: 'subjek_hukum'},
                {data: 'nomor_kkprl', name: 'nomor_kkprl'},
                {data: 'sanksi', name: 'sanksi'},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
            ]
        });

        $(document).on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            var url = "{{ route('surat-peringatan.destroy', ':id') }}";
            url = url.replace(':id', id);

            if(confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if(response.success) {
                            table.ajax.reload();
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('Terjadi kesalahan sistem');
                    }
                });
            }
        });
    });
</script>
@endpush
