@extends('layouts.app')

@section('title', 'Pelaku Usaha')
@section('page-title', 'Master Pelaku Usaha')
@section('breadcrumb')
    <li class="breadcrumb-item">Master Data</li>
    <li class="breadcrumb-item active">Pelaku Usaha</li>
@endsection

@section('content')
<div class="card card-primary card-outline fade-in">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h3 class="card-title mb-0"><i class="fas fa-building me-2"></i>Data Pelaku Usaha</h3>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('pelaku-usaha.export-excel') }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-file-excel me-1"></i> <span class="d-none d-sm-inline">Excel</span></a>
            <a href="{{ route('pelaku-usaha.export-pdf') }}" class="btn btn-outline-danger btn-sm"><i class="fas fa-file-pdf me-1"></i> <span class="d-none d-sm-inline">PDF</span></a>
        </div>
    </div>

    <div class="card-body">
        {{-- Filters --}}
        <div class="row mb-3 g-2">
            <div class="col-lg-3 col-md-6 col-6">
                <select id="filterProvinsi" class="form-select select2" data-placeholder="Semua Provinsi">
                    <option value="">Semua Provinsi</option>
                    @foreach($provinsis as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 col-md-6 col-6">
                <select id="filterJenisUsaha" class="form-select select2" data-placeholder="Semua Jenis Usaha">
                    <option value="">Semua Jenis Usaha</option>
                    @foreach($jenisUsahas as $j)
                        <option value="{{ $j->id }}">{{ $j->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 col-md-6 col-6">
                <select id="filterStatus" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="tidak_aktif">Tidak Aktif</option>
                    <option value="dalam_proses">Dalam Proses</option>
                    <option value="bermasalah">Bermasalah</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-6 col-6">
                <input type="text" id="filterSearch" class="form-control" placeholder="Cari nama / PKKPRL...">
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table id="tabelPelakuUsaha" class="table table-striped table-hover align-middle w-100">
                <thead>
                    <tr>
                        <th width="40">No</th>
                        <th>Nama Perusahaan</th>
                        <th class="d-none d-md-table-cell" width="200">Jenis Pengawasan</th>
                        <th class="d-none d-lg-table-cell">Jenis Usaha</th>
                        <th class="d-none d-lg-table-cell">Wilayah</th>
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
    let table = $('#tabelPelakuUsaha').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "{{ route('pelaku-usaha.data') }}",
            data: function (d) {
                d.provinsi_id = $('#filterProvinsi').val();
                d.jenis_usaha_id = $('#filterJenisUsaha').val();
                d.status = $('#filterStatus').val();
                d.search_custom = $('#filterSearch').val();
                d.search = { value: $('#filterSearch').val() };
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'nama_perusahaan' },
            { data: 'jenis_pengawasan', defaultContent: '-', className: 'd-none d-md-table-cell', orderable: false, searchable: false },
            { data: 'jenis_usaha', className: 'd-none d-lg-table-cell' },
            { data: 'wilayah', className: 'd-none d-lg-table-cell' },
            { data: 'status_badge', orderable: false, className: 'text-center' },
            { data: 'aksi', orderable: false, searchable: false, className: 'text-center' },
        ]
    });

    $('#filterProvinsi, #filterJenisUsaha, #filterStatus').on('change', () => table.draw());
    let searchTimer;
    $('#filterSearch').on('keyup', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => table.draw(), 400);
    });

    function hapusPelakuUsaha(id) {
        confirmDelete(`/pelaku-usaha/${id}`, () => table.draw());
    }
</script>
@endpush
