@extends('layouts.app')
@section('title', 'Dokumen Pelaku Usaha')
@section('page-title', 'Dokumen Pelaku Usaha')
@section('breadcrumb')<li class="breadcrumb-item active">Dokumen</li>@endsection
@section('content')
<div class="card card-primary card-outline fade-in">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h3 class="card-title mb-0"><i class="fas fa-folder-open me-2"></i>Dokumen Pelaku Usaha</h3>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalDokumen"><i class="fas fa-upload me-1"></i> Upload Dokumen</button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tabelDokumen" class="table table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th width="40">No</th>
                        <th>Perusahaan</th>
                        <th class="d-none d-md-table-cell">Nama PIC</th>
                        <th class="d-none d-lg-table-cell">Jabatan</th>
                        <th>Jenis Dokumen</th>
                        <th class="d-none d-sm-table-cell">Tanggal Upload</th>
                        <th width="120">Dokumen</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

@push('modals')
<div class="modal fade" id="modalDokumen" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('dokumen.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-cloud-upload-alt me-2 text-primary"></i>Upload Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Pelaku Usaha <span class="text-danger">*</span></label>
                            <select name="pelaku_usaha_id" class="form-select select2-tags" required style="width: 100%;">
                                <option value="">-- Pilih atau Ketik Baru --</option>
                                @foreach($pelakuUsahas as $p)<option value="{{ $p->id }}">{{ $p->nama_perusahaan }}</option>@endforeach
                            </select>
                        </div>

                        {{-- Nama & Jabatan --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Penanggung Jawab <span class="text-danger">*</span></label>
                            <input type="text" name="nama_pic" class="form-control" required placeholder="Nama lengkap">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jabatan <span class="text-danger">*</span></label>
                            <input type="text" name="jabatan" class="form-control" required placeholder="Jabatan">
                        </div>

                        {{-- Nomor Telepon & Email --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nomor yang Bisa Dihubungi <span class="text-danger">*</span></label>
                            <input type="text" name="nomor_hp" class="form-control" required placeholder="Nomor telepon">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email Perusahaan <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required placeholder="Alamat email">
                        </div>

                        {{-- Dokumen Diunggah --}}
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Dokumen/Perizinan yang Diunggah <span class="text-danger">*</span></label>
                            <textarea name="jenis_dokumen" class="form-control" rows="5" required placeholder="Tuliskan seluruh dokumen yang Anda lampirkan."></textarea>
                        </div>

                        {{-- File --}}
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">File Dokumen <span class="text-danger">*</span></label>
                            <input type="file" name="file" class="form-control" required>
                            <div class="form-text">Format: PDF, JPG, PNG. Maksimal 10MB.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-upload me-1"></i> Upload</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endpush
@endsection
@push('scripts')
<script>
let table = $('#tabelDokumen').DataTable({
    processing: true, serverSide: true, responsive: true,
    ajax: "{{ route('dokumen.data') }}",
    columns: [
        { 
            data: 'DT_RowIndex', 
            orderable: false, 
            searchable: false, 
            className: 'text-center',
            render: function(data, type, row) {
                let badge = row.is_manual ? '<span class="badge rounded-pill" style="background-color: #fd7e14; color: white; padding: 0.35em 0.65em; font-size: 0.75em;" title="Diunggah oleh Admin">A</span>' : '';
                return '<div style="display: grid; grid-template-columns: 28px 1fr; align-items: center; gap: 4px; min-width: 50px;">' +
                       '<div style="text-align: right;">' + badge + '</div>' +
                       '<div style="text-align: center;">' + data + '</div>' +
                       '</div>';
            }
        },
        { data: 'perusahaan' },
        { data: 'nama_pic', defaultContent:'-', className: 'd-none d-md-table-cell' },
        { data: 'jabatan', defaultContent:'-', className: 'd-none d-lg-table-cell' },
        { data: 'jenis_dokumen' },
        { data: 'tanggal', className: 'd-none d-sm-table-cell' },
        { data: 'aksi', orderable: false, searchable: false, className: 'text-center' },
    ]
});

function hapusDokumen(id, isManual) {
    let url = isManual ? `/dokumen/${id}` : `/dokumen/company/${id}`;
    confirmDelete(url, () => table.draw());
}

// Reset select2 on modal hidden
$('#modalDokumen').on('hidden.bs.modal', function () {
    $(this).find('form')[0].reset();
    $(this).find('.select2, .select2-tags').val('').trigger('change');
});

// Initialize select2 with tags for Pelaku Usaha
$('.select2-tags').select2({
    theme: 'bootstrap-5',
    width: '100%',
    placeholder: '-- Pilih atau Ketik Baru --',
    tags: true,
    dropdownParent: $('#modalDokumen')
});
</script>
@endpush
