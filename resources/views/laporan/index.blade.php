@extends('layouts.app')
@section('title', 'Laporan')
@section('page-title', 'Laporan Pengawasan')
@section('breadcrumb')<li class="breadcrumb-item active">Laporan</li>@endsection

@section('content')
<div class="row justify-content-center fade-in">
    <div class="col-lg-10">
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-header border-bottom-0 pt-4 pb-0">
                <div class="text-center mb-4">
                    <div class="bg-primary-soft text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="fas fa-file-invoice fa-2x"></i>
                    </div>
                    <h3 class="fw-bold text-dark">Filter & Cetak Laporan</h3>
                    <p class="text-muted">Pilih parameter di bawah ini untuk menghasilkan laporan pengawasan yang Anda butuhkan.</p>
                </div>
            </div>
            
            <div class="card-body px-4 px-md-5 pb-5">
                <form method="POST" action="" id="formLaporan">
                    @csrf
                    
                    <div class="row g-4 mb-5">
                        {{-- Periode Tanggal --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Periode Laporan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="far fa-calendar-alt text-muted"></i></span>
                                <input type="date" name="dari_tanggal" class="form-control" title="Dari Tanggal">
                                <span class="input-group-text bg-light text-muted border-start-0 border-end-0">s.d</span>
                                <input type="date" name="sampai_tanggal" class="form-control" title="Sampai Tanggal">
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status Pelaku Usaha</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="aktif">Aktif</option>
                                <option value="tidak_aktif">Tidak Aktif</option>
                                <option value="dalam_proses">Dalam Proses</option>
                                <option value="bermasalah">Bermasalah</option>
                            </select>
                        </div>

                        {{-- Provinsi --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Wilayah Provinsi</label>
                            <select name="provinsi_id" class="form-select select2">
                                <option value="">Semua Provinsi</option>
                                @foreach($provinsis as $p)<option value="{{ $p->id }}">{{ $p->nama }}</option>@endforeach
                            </select>
                        </div>

                        {{-- Jenis Usaha --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kategori Jenis Usaha</label>
                            <select name="jenis_usaha_id" class="form-select select2">
                                <option value="">Semua Kategori</option>
                                @foreach($jenisUsahas as $j)<option value="{{ $j->id }}">{{ $j->nama }}</option>@endforeach
                            </select>
                        </div>
                    </div>

                    <hr class="mb-4">

                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <button type="button" onclick="submitLaporan('{{ route('laporan.export-excel') }}')" class="btn btn-success px-4 py-2" style="border-radius: 8px;">
                            <i class="fas fa-file-excel me-2"></i> Export Excel
                        </button>
                        <button type="button" onclick="submitLaporan('{{ route('laporan.export-pdf') }}')" class="btn btn-danger px-4 py-2" style="border-radius: 8px;">
                            <i class="fas fa-file-pdf me-2"></i> Export PDF
                        </button>
                        <button type="button" onclick="window.print()" class="btn btn-secondary px-4 py-2" style="border-radius: 8px;">
                            <i class="fas fa-print me-2"></i> Cetak Halaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function submitLaporan(url) {
        const form = $('#formLaporan');
        form.attr('action', url);
        form.attr('target', '_blank');
        form.submit();
    }
</script>
@endpush
