@extends('layouts.app')
@section('title', 'Jadwal Pengawasan')
@section('page-title', 'Jadwal Pengawasan')
@section('breadcrumb')<li class="breadcrumb-item">Pengawasan</li><li class="breadcrumb-item active">Jadwal</li>@endsection
@section('content')
<div class="card card-primary card-outline fade-in">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h3 class="card-title mb-0"><i class="fas fa-calendar-days me-2"></i>Jadwal Pengawasan</h3>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalJadwal"><i class="fas fa-plus me-1"></i> Tambah Jadwal</button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tabelJadwal" class="table table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th width="40">No</th>
                        <th>Perusahaan</th>
                        <th class="d-none d-sm-table-cell">Jenis</th>
                        <th>Tanggal Rencana</th>
                        <th class="d-none d-md-table-cell">Tim</th>
                        <th>Status</th>
                        <th width="80">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalJadwal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="formJadwal" action="{{ route('jadwal.store') }}">
            @csrf
            <input type="hidden" name="_method" id="methodJadwal" value="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-calendar-plus me-2 text-primary"></i>Jadwal Pengawasan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pelaku Usaha <span class="text-danger">*</span></label>
                        <select name="pelaku_usaha_id" class="form-select select2" required style="width: 100%;">
                            <option value="">-- Pilih Pelaku Usaha --</option>
                            @foreach($pelakuUsahas as $p)<option value="{{ $p->id }}">{{ $p->nama_perusahaan }}</option>@endforeach
                        </select>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6 col-12">
                            <label class="form-label">Jenis Pengawasan <span class="text-danger">*</span></label>
                            <select name="jenis_pengawasan" class="form-select" required>
                                <option value="prl">PRL</option><option value="alse">ALSE</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label">Tanggal Rencana <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_rencana" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tim Pengawas</label>
                        <input type="text" name="tim_pengawas" class="form-control" placeholder="Tim pengawas">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="belum_dilaksanakan">Belum Dilaksanakan</option>
                            <option value="sedang_berjalan">Sedang Berjalan</option>
                            <option value="selesai">Selesai</option>
                            <option value="dibatalkan">Dibatalkan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="3" placeholder="Tambahkan catatan jika perlu..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
    let table = $('#tabelJadwal').DataTable({
        processing: true, serverSide: true, responsive: true,
        ajax: "{{ route('jadwal.data') }}",
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'perusahaan' },
            { data: 'jenis_pengawasan', className: 'd-none d-sm-table-cell text-uppercase text-center' },
            { data: 'tanggal' },
            { data: 'tim_pengawas', defaultContent: '-', className: 'd-none d-md-table-cell' },
            { data: 'status_badge', orderable: false, className: 'text-center' },
            { data: 'aksi', orderable: false, searchable: false, className: 'text-center' },
        ]
    });

    // Reset modal on hide
    $('#modalJadwal').on('hidden.bs.modal', function () {
        $('#formJadwal')[0].reset();
        $('#formJadwal .select2').val('').trigger('change');
        $('#methodJadwal').val('POST');
        $('#formJadwal').attr('action', '{{ route("jadwal.store") }}');
        $(this).find('.modal-title').html('<i class="fas fa-calendar-plus me-2 text-primary"></i>Tambah Jadwal');
    });

    function hapusJadwal(id) {
        confirmDelete(`/jadwal/${id}`, () => table.draw());
    }
</script>
@endpush
