@extends('layouts.app')
@section('title', 'Pengawasan Tidak Langsung')
@section('page-title', 'Pengawasan Tidak Langsung')
@section('breadcrumb')<li class="breadcrumb-item">Pengawasan</li><li class="breadcrumb-item active">Pengawasan Tidak Langsung</li>@endsection
@section('content')
<div class="card card-primary card-outline fade-in">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h3 class="card-title mb-0"><i class="fas fa-file-lines me-2"></i>Daftar Pengawasan Tidak Langsung</h3>
        <a href="{{ route('pengawasan-tidak-langsung.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Buat Form</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tabelPengawasanTidakLangsung" class="table table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th width="40">No</th>
                        <th>Nomor Form</th>
                        <th>Perusahaan</th>
                        <th class="d-none d-md-table-cell">Tanggal Laporan</th>
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
    let table = $('#tabelPengawasanTidakLangsung').DataTable({
        processing: true, serverSide: true, responsive: true,
        ajax: "{{ route('pengawasan-tidak-langsung.data') }}",
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'nomor' },
            { data: 'perusahaan' },
            { data: 'tanggal_laporan', className: 'd-none d-md-table-cell' },
            { data: 'status_badge', orderable: false, className: 'text-center' },
            { data: 'aksi', orderable: false, searchable: false, className: 'text-center' },
        ]
    });

    function hapusData(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('pengawasan-tidak-langsung') }}/" + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire('Terhapus!', response.message, 'success');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Terjadi kesalahan saat menghapus data.', 'error');
                    }
                });
            }
        });
    }
</script>
@endpush
