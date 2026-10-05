@extends('layouts.app')
@section('title', 'Arsip Dokumen Lama BA')
@section('page-title', 'Arsip Dokumen Lama & Google Drive BA')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Arsip Dokumen BA</li>
@endsection

@php
    $isAdmin = auth()->check() && auth()->user()->hasAnyRole(['super-admin', 'admin']);
@endphp

@section('content')
<div class="row g-3 mb-4 fade-in">
    {{-- STATS CARDS --}}
    <div class="col-md-4 col-12">
        <div class="card card-outline card-primary shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="bg-primary-soft text-primary rounded-3 p-3 me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                    <i class="fas fa-archive fa-2x"></i>
                </div>
                <div>
                    <h6 class="text-muted small fw-semibold text-uppercase mb-0">Total Seluruh Arsip</h6>
                    <h3 class="fw-bold mb-0 text-dark">{{ number_format($stats['total'] ?? 0) }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="card card-outline card-info shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="bg-info-soft text-info rounded-3 p-3 me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                    <i class="fas fa-file-lines fa-2x"></i>
                </div>
                <div>
                    <h6 class="text-muted small fw-semibold text-uppercase mb-0">Berkas File Terunggah</h6>
                    <h3 class="fw-bold mb-0 text-dark">{{ number_format($stats['files'] ?? 0) }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="card card-outline card-success shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="bg-success-soft text-success rounded-3 p-3 me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                    <i class="fab fa-google-drive fa-2x"></i>
                </div>
                <div>
                    <h6 class="text-muted small fw-semibold text-uppercase mb-0">Tautan Google Drive</h6>
                    <h3 class="fw-bold mb-0 text-dark">{{ number_format($stats['links'] ?? 0) }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-primary card-outline shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="card-title fw-bold mb-0 text-dark"><i class="fas fa-folder-open me-2 text-primary"></i>Daftar Arsip Dokumen Seluruh BA</h5>
            <small class="text-muted">Dokumen fisik dan tautan Google Drive sebelum website ini dirilis</small>
        </div>
        @if($isAdmin)
            <button type="button" class="btn btn-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambahArsipPusat">
                <i class="fas fa-plus me-1"></i> Tambah Arsip Dokumen
            </button>
        @endif
    </div>

    <div class="card-body border-bottom bg-light py-3">
        {{-- FILTER FORM --}}
        <form method="GET" action="{{ route('arsip-dokumen-ba.index') }}" class="row g-2 align-items-center">
            <div class="col-md-3 col-12">
                <select name="tipe_ba" class="form-select form-select-sm">
                    <option value="">-- Semua Jenis BA --</option>
                    <option value="ba-was-prl" @selected(request('tipe_ba') === 'ba-was-prl')>BA WAS PRL</option>
                    <option value="ba-was-alse" @selected(request('tipe_ba') === 'ba-was-alse')>BA WAS ALSE</option>
                    <option value="ba-reklamasi" @selected(request('tipe_ba') === 'ba-reklamasi')>BA Reklamasi</option>
                    <option value="ba-ppk" @selected(request('tipe_ba') === 'ba-ppk')>BA PPK</option>
                    <option value="ba-pencemaran" @selected(request('tipe_ba') === 'ba-pencemaran')>BA Pencemaran</option>
                    <option value="surat-peringatan" @selected(request('tipe_ba') === 'surat-peringatan')>Surat Peringatan</option>
                </select>
            </div>
            <div class="col-md-3 col-12">
                <select name="tipe" class="form-select form-select-sm">
                    <option value="">-- Semua Format --</option>
                    <option value="file" @selected(request('tipe') === 'file')>Berkas File (PDF/Doc/dll)</option>
                    <option value="link" @selected(request('tipe') === 'link')>Tautan Google Drive</option>
                </select>
            </div>
            <div class="col-md-4 col-12">
                <div class="input-group input-group-sm">
                    <input type="text" name="q" class="form-control" placeholder="Cari judul, nama file, atau catatan..." value="{{ request('q') }}">
                    <button class="btn btn-secondary" type="submit"><i class="fas fa-search me-1"></i> Filter</button>
                </div>
            </div>
            <div class="col-md-2 col-12 text-md-end">
                @if(request()->anyFilled(['tipe_ba', 'tipe', 'q']))
                    <a href="{{ route('arsip-dokumen-ba.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                        <i class="fas fa-times me-1"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="card-body p-0">
        @if($arsipList->isEmpty())
            <div class="text-center py-5">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center p-3 mb-3 text-muted" style="width: 70px; height: 70px;">
                    <i class="fas fa-folder-open fa-2x"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">Tidak Ada Data Arsip</h6>
                <p class="text-muted small mb-3">Belum ada dokumen arsip atau tautan Google Drive yang sesuai dengan filter.</p>
                @if($isAdmin)
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahArsipPusat">
                        <i class="fas fa-plus me-1"></i> Unggah Arsip Sekarang
                    </button>
                @endif
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 45px;" class="text-center">#</th>
                            <th style="width: 220px;">Jenis &amp; Nomor BA</th>
                            <th>Nama &amp; Keterangan Dokumen</th>
                            <th style="width: 140px;">Format / Tipe</th>
                            <th style="width: 160px;">Pengunggah</th>
                            <th style="width: 170px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($arsipList as $index => $item)
                            <tr id="arsip-row-{{ $item->id }}">
                                <td class="text-center text-muted fw-bold">
                                    {{ $arsipList->firstItem() + $index }}
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary border mb-1">
                                        {{ $item->nama_tipe_ba }}
                                    </span>
                                    <div class="fw-bold text-dark text-break">
                                        {{ $item->arsipable->nomor_ba ?? '-' }}
                                    </div>
                                    @if($item->route_show)
                                        <a href="{{ $item->route_show }}" class="small text-decoration-none text-muted" target="_blank" title="Buka Detail BA">
                                            <i class="fas fa-arrow-up-right-from-square me-1"></i>Lihat Detail BA
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-start gap-2">
                                        <i class="{{ $item->icon_class }} fa-2x mt-1 flex-shrink-0"></i>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $item->judul }}</div>
                                            @if($item->isFile())
                                                <div class="text-muted small text-break">{{ $item->nama_file }}</div>
                                            @elseif($item->isLink())
                                                <div class="text-muted small text-break"><i class="fab fa-google-drive text-success me-1"></i>Google Drive Link</div>
                                            @endif
                                            @if($item->keterangan)
                                                <div class="text-secondary small mt-1"><i class="fas fa-info-circle me-1 text-info"></i>{{ $item->keterangan }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($item->isLink())
                                        <span class="badge bg-success-subtle text-success border px-2 py-1 fw-semibold">
                                            <i class="fab fa-google-drive me-1"></i> G-DRIVE
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border px-2 py-1 text-uppercase fw-semibold">
                                            {{ $item->ekstensi ?: 'FILE' }}
                                        </span>
                                        @if($item->ukuran_formatted)
                                            <div class="text-muted small mt-1">{{ $item->ukuran_formatted }}</div>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <div class="small fw-semibold text-dark">{{ $item->uploader->name ?? 'Admin' }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $item->created_at->format('d/m/Y H:i') }}</div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        @if($item->isLink())
                                            <a href="{{ $item->link_gdrive }}" target="_blank" rel="noopener noreferrer" class="btn btn-success fw-semibold" title="Buka Link Google Drive">
                                                <i class="fab fa-google-drive me-1"></i> Buka Drive
                                            </a>
                                        @else
                                            @if(in_array($item->ekstensi, ['pdf', 'jpg', 'jpeg', 'png']))
                                                <a href="{{ route('arsip-dokumen-ba.download', ['arsipDokumenBa' => $item->id, 'preview' => 1]) }}" target="_blank" class="btn btn-outline-primary" title="Buka / Preview di Tab Baru">
                                                    <i class="fas fa-eye me-1"></i> Buka
                                                </a>
                                            @endif
                                            <a href="{{ route('arsip-dokumen-ba.download', $item->id) }}" class="btn btn-primary" title="Unduh File">
                                                <i class="fas fa-download me-1"></i> Unduh
                                            </a>
                                        @endif

                                        @if($isAdmin)
                                            <button type="button" class="btn btn-outline-danger" title="Hapus Arsip" onclick="hapusArsipPusat('{{ route('arsip-dokumen-ba.destroy', $item->id) }}', '{{ $item->id }}')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($arsipList->hasPages())
                <div class="p-3 border-top d-flex justify-content-end">
                    {{ $arsipList->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection

@if($isAdmin)
@push('modals')
{{-- MODAL TAMBAH ARSIP PUSAT --}}
<div class="modal fade" id="modalTambahArsipPusat" tabindex="-1" aria-labelledby="modalTambahArsipPusatLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('arsip-dokumen-ba.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="modalTambahArsipPusatLabel">
                        <i class="fas fa-archive me-2"></i> Tambah Arsip Dokumen / Link Google Drive BA
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-3">
                        {{-- Pilih Tipe BA --}}
                        <div class="col-md-6 col-12">
                            <label class="form-label fw-semibold">Pilih Jenis Berita Acara (BA) <span class="text-danger">*</span></label>
                            <select name="arsipable_type" id="selectTipeBaPusat" class="form-select" required onchange="gantiPilihanBa(this.value)">
                                <option value="">-- Pilih Jenis BA --</option>
                                <option value="ba-was-prl">BA WAS PRL</option>
                                <option value="ba-was-alse">BA WAS ALSE</option>
                                <option value="ba-reklamasi">BA Reklamasi</option>
                                <option value="ba-ppk">BA PPK</option>
                                <option value="ba-pencemaran">BA Pencemaran</option>
                                <option value="surat-peringatan">Surat Peringatan</option>
                            </select>
                        </div>

                        {{-- Pilih Record BA --}}
                        <div class="col-md-6 col-12">
                            <label class="form-label fw-semibold">Pilih Nomor BA Terkait <span class="text-danger">*</span></label>
                            <select name="arsipable_id" id="selectRecordBaPusat" class="form-select" required disabled>
                                <option value="">-- Pilih Jenis BA Dahulu --</option>
                            </select>
                        </div>

                        {{-- Pilihan Tipe Input: File atau Link GDrive --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Bentuk Arsip <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipe" id="tipeFileRadio" value="file" checked onchange="toggleTipeInput('file')">
                                    <label class="form-check-label fw-semibold" for="tipeFileRadio">
                                        <i class="fas fa-file-upload text-primary me-1"></i> Unggah Berkas Dokumen (File)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipe" id="tipeLinkRadio" value="link" onchange="toggleTipeInput('link')">
                                    <label class="form-check-label fw-semibold" for="tipeLinkRadio">
                                        <i class="fab fa-google-drive text-success me-1"></i> Tautan / Link Google Drive
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Section File Upload --}}
                        <div id="sectionInputFile" class="col-12">
                            <div class="p-3 border rounded-3 bg-light">
                                <label class="form-label fw-semibold">Pilih Berkas Dokumen <span class="text-danger">*</span></label>
                                <input type="file" name="files_arsip[]" id="inputFileArsip" class="form-control" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip,.rar">
                                <div class="form-text text-muted">Bisa memilih beberapa berkas sekaligus (PDF, Word, Excel, Gambar scan, ZIP). Maks 100MB/file.</div>
                            </div>
                        </div>

                        {{-- Section Google Drive Link --}}
                        <div id="sectionInputLink" class="col-12" style="display: none;">
                            <div class="p-3 border rounded-3 bg-light">
                                <label class="form-label fw-semibold">URL Link Google Drive <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fab fa-google-drive text-success"></i></span>
                                    <input type="text" name="link_gdrive" id="inputLinkGdrive" class="form-control" placeholder="https://drive.google.com/drive/folders/...">
                                </div>
                                <div class="form-text text-muted">Tautan folder atau file di Google Drive yang mengarahkan ke arsip dokumen sebelumnya.</div>
                            </div>
                        </div>

                        {{-- Judul --}}
                        <div class="col-md-6 col-12">
                            <label class="form-label fw-semibold">Judul / Nama Arsip <small class="text-muted fw-normal">(Opsional)</small></label>
                            <input type="text" name="judul" class="form-control" placeholder="Contoh: Berkas Scan BA Lapangan 2022">
                        </div>

                        {{-- Catatan --}}
                        <div class="col-md-6 col-12">
                            <label class="form-label fw-semibold">Keterangan / Catatan <small class="text-muted fw-normal">(Opsional)</small></label>
                            <input type="text" name="keterangan" class="form-control" placeholder="Catatan informasi tambahan mengenai berkas">
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-semibold">
                        <i class="fas fa-save me-1"></i> Simpan Arsip
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
const dataBaPusat = @json($baList);

function gantiPilihanBa(tipe) {
    const select = $('#selectRecordBaPusat');
    select.empty();

    if (!tipe || !dataBaPusat[tipe] || dataBaPusat[tipe].length === 0) {
        select.append('<option value="">-- Tidak ada BA ditemukan --</option>');
        select.prop('disabled', true);
        return;
    }

    select.append('<option value="">-- Pilih Nomor BA --</option>');
    dataBaPusat[tipe].forEach(item => {
        select.append(`<option value="${item.id}">${item.nomor_ba}</option>`);
    });
    select.prop('disabled', false);
}

function toggleTipeInput(tipe) {
    if (tipe === 'file') {
        $('#sectionInputFile').slideDown();
        $('#sectionInputLink').slideUp();
        $('#inputFileArsip').prop('required', true);
        $('#inputLinkGdrive').prop('required', false);
    } else {
        $('#sectionInputFile').slideUp();
        $('#sectionInputLink').slideDown();
        $('#inputFileArsip').prop('required', false);
        $('#inputLinkGdrive').prop('required', true);
    }
}

function hapusArsipPusat(url, id) {
    if (typeof confirmDelete === 'function') {
        confirmDelete(url, function() {
            $('#arsip-row-' + id).fadeOut(300, function() { $(this).remove(); });
        });
    } else {
        if (confirm('Hapus dokumen arsip ini?')) {
            $.ajax({
                url: url,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function() {
                    $('#arsip-row-' + id).fadeOut(300, function() { $(this).remove(); });
                }
            });
        }
    }
}
</script>
@endpush
@endif
