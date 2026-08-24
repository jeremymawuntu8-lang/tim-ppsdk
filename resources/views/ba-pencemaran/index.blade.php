@extends('layouts.app')
@section('title', 'Data BA Pencemaran')
@section('page-title', 'Data BA Pencemaran')
@section('breadcrumb')<li class="breadcrumb-item">Pengawasan</li><li class="breadcrumb-item active">BA Pencemaran</li>@endsection
@section('content')
<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-file-lines me-2"></i>Data Berita Acara Pencemaran
                    </h5>
                    <a href="{{ route('ba-pencemaran.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Tambah Data
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dt-responsive nowrap" id="tabelBaPencemaran" width="100%">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nomor BA</th>
                                    <th>Tanggal</th>
                                    <th>Perusahaan / PJ</th>
                                    <th>Lokasi</th>
                                    <th width="10%">Status</th>
                                    <th width="15%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let table = $('#tabelBaPencemaran').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('ba-pencemaran.data') }}",
                data: function(d) {
                    // Optional filters can be added here
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'nomor_ba', name: 'nomor_ba' },
                { data: 'tanggal', name: 'tanggal_pengawasan' },
                { data: 'perusahaan', name: 'perusahaan', orderable: false },
                { data: 'lokasi_pengawasan', name: 'lokasi_pengawasan' },
                { data: 'status_badge', name: 'status', className: 'text-center' },
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: 'text-center' }
            ],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
            }
        });

        // Hapus Data
        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id');
            let url = "{{ route('ba-pencemaran.destroy', ':id') }}".replace(':id', id);

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data BA Pencemaran yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Terhapus!', response.message, 'success');
                                table.ajax.reload();
                            } else {
                                Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Gagal!', 'Terjadi kesalahan sistem.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
