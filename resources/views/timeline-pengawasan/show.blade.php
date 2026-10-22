@extends('layouts.app')
@section('title', 'Detail Tindak Lanjut - ' . $pelakuUsaha->nama_perusahaan)
@section('page-title', 'Detail Tindak Lanjut')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('timeline-pengawasan.index') }}">Tindak Lanjut</a></li>
    <li class="breadcrumb-item active">{{ \Illuminate\Support\Str::limit($pelakuUsaha->nama_perusahaan, 35) }}</li>
@endsection

@php
    $events = $timeline['events'];
    $countBy = $events->groupBy('stage')->map->count();
    $currentColor = [
        'belum' => 'secondary',
        'pemberitahuan' => 'primary',
        'pengawasan' => 'info',
        'saran' => 'warning',
        'keterangan' => 'danger',
        'supervisi' => 'purple',
        'ekspose' => 'teal',
        'billing' => 'indigo',
        'selesai' => 'success'
    ];

    // Default tab mengikuti tahap aktif saat ini ($timeline['current'])
    $defaultTab = match ($timeline['current']) {
        'selesai' => 'billing',
        'belum' => 'pemberitahuan',
        default => $timeline['current'],
    };
    $activeTab = request('tab', $defaultTab);

    // Records tahapan
    $pemberitahuanRecord = $pelakuUsaha->timelineTahapans->firstWhere('tahap', 'pemberitahuan');
    $pengawasanRecord = $pelakuUsaha->timelineTahapans->firstWhere('tahap', 'pengawasan');
    $saranRecord = $pelakuUsaha->timelineTahapans->firstWhere('tahap', 'saran');
    $keteranganRecord = $pelakuUsaha->timelineTahapans->firstWhere('tahap', 'keterangan');
    $supervisiRecord = $pelakuUsaha->timelineTahapans->firstWhere('tahap', 'supervisi');
    $eksposeRecord = $pelakuUsaha->timelineTahapans->firstWhere('tahap', 'ekspose');
    $billingRecord = $pelakuUsaha->timelineTahapans->firstWhere('tahap', 'billing');
    $activeRecord = $pelakuUsaha->timelineTahapans->firstWhere('tahap', $activeTab);

    // Berkas Pemberitahuan (1 dokumen opsional)
    $pemberitahuanFile = $pemberitahuanRecord ? $pemberitahuanRecord->files->first() : null;

    // Files untuk supervisi
    $bahanPaparan = $supervisiRecord ? $supervisiRecord->files->firstWhere('kategori', 'bahan_paparan') : null;
    $permohonanSupervisi = $supervisiRecord ? $supervisiRecord->files->firstWhere('kategori', 'permohonan_supervisi') : null;
    $petaSpasials = $supervisiRecord ? $supervisiRecord->files->where('kategori', 'peta_spasial')->sortBy('slot') : collect();
    $dokumenTambahans = $supervisiRecord ? $supervisiRecord->files->where('kategori', 'dokumen_tambahan')->sortByDesc('created_at') : collect();

    // Cek status tahap
    $isSupervisiDone = ($supervisiRecord && in_array($supervisiRecord->status, ['selesai', 'skip']));
    $isEksposeDone = ($eksposeRecord && in_array($eksposeRecord->status, ['selesai', 'skip']));
    $isBillingDone = ($billingRecord && in_array($billingRecord->status, ['selesai', 'skip']));
@endphp

@section('content')
<div class="fade-in">
    {{-- Notifikasi Flash --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="fas fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="fas fa-triangle-exclamation me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Header & Stepper --}}
    <div class="card card-primary card-outline shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center gap-4">
                <div class="tl-ring" style="--p: {{ $timeline['progress'] }}">
                    <div class="tl-ring-inner">
                        <strong>{{ $timeline['progress'] }}%</strong>
                        <span>Progres</span>
                    </div>
                </div>
                <div class="flex-fill">
                    <h4 class="fw-bold text-dark mb-1">{{ $pelakuUsaha->nama_perusahaan }}</h4>
                    <div class="text-muted small mb-2">
                        <span class="me-3"><i class="fas fa-id-card me-1"></i>{{ $pelakuUsaha->nomor_pkkprl ?? 'PKKPRL -' }}</span>
                        <span class="me-3"><i class="fas fa-location-dot me-1"></i>{{ $pelakuUsaha->kabupaten->nama ?? '-' }}, {{ $pelakuUsaha->provinsi->nama ?? '-' }}</span>
                        @if($pelakuUsaha->jenisUsaha)<span><i class="fas fa-tag me-1"></i>{{ $pelakuUsaha->jenisUsaha->nama }}</span>@endif
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <span class="text-muted small">Tahap saat ini:</span>
                        <span class="tl-stage-tag tl-c-{{ $currentColor[$timeline['current']] ?? 'secondary' }}" style="font-size:.75rem">{{ $timeline['current_label'] }}</span>
                        @if($timeline['perlu_tindakan'])
                            <span class="tl-badge tl-badge-sp"><i class="fas fa-bell me-1"></i>Perlu tindak lanjut</span>
                        @endif
                        @if($timeline['last_activity'])
                            <span class="text-muted small ms-2"><i class="far fa-clock me-1"></i>Aktivitas terakhir {{ $timeline['last_activity']->diffForHumans() }}</span>
                        @endif
                    </div>
                </div>
                <div class="d-flex flex-column gap-2">
                    @can('kelola-master-data')
                        <a href="{{ route('pelaku-usaha.show', $pelakuUsaha->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-building me-1"></i> Detail Pelaku Usaha</a>
                    @endcan
                    <a href="{{ route('timeline-pengawasan.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar</a>
                </div>
            </div>

            <hr class="my-4">

            {{-- Stepper 7 Tahapan (Interaktif & Dapat Diklik) --}}
            <x-timeline-stepper :stages="$timeline['stages']" :clickable="true" :active-tab="$activeTab" />
        </div>
    </div>

    {{-- ==================== BAR NAVIGASI TAHAPAN (TAB PILLS) ==================== --}}
    @php
        $stageOrderList = ['pemberitahuan', 'pengawasan', 'saran', 'keterangan', 'supervisi', 'ekspose', 'billing'];
        $stageNumberLabels = [
            'pemberitahuan' => '1. Pemberitahuan',
            'pengawasan'    => '2. Pengawasan',
            'saran'         => '3. Saran / Tindak Lanjut',
            'keterangan'    => '4. Pemeriksaan Keterangan',
            'supervisi'     => '5. Supervisi',
            'ekspose'       => '6. Ekspose',
            'billing'       => '7. Billing',
        ];
    @endphp
    <div class="card shadow-sm border-0 mb-4 bg-white">
        <div class="card-body p-2">
            <div class="d-flex align-items-center justify-content-between px-3 pt-2 pb-2 border-bottom mb-2 flex-wrap gap-2">
                <span class="small fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="fas fa-layer-group text-primary"></i>
                    <span>Tinjau Data Tahap Pengawasan</span>
                </span>
                <span class="small text-muted">
                    <i class="fas fa-circle-info text-info me-1"></i> Klik salah satu tahap di bawah atau di stepper atas untuk meninjau/memeriksa data sebelumnya
                </span>
            </div>
            <ul class="nav nav-pills nav-fill flex-nowrap overflow-auto gap-2 py-1 px-1" style="white-space: nowrap;">
                @foreach($stageOrderList as $stKey)
                    @php
                        $stData = $timeline['stages'][$stKey] ?? null;
                        if (!$stData) continue;
                        $isTabActive = ($activeTab === $stKey);
                        $badgeBg = match($stData['state']) {
                            'done' => 'bg-success',
                            'skipped' => 'bg-secondary',
                            'active' => 'bg-primary',
                            'alert' => 'bg-danger',
                            default => 'bg-light text-muted border',
                        };
                        $badgeTxt = match($stData['state']) {
                            'done' => 'Selesai',
                            'skipped' => 'Dilewati',
                            'active' => 'Sedang Berjalan',
                            'alert' => 'Perlu Tindakan',
                            default => 'Belum',
                        };
                    @endphp
                    <li class="nav-item">
                        <a href="?tab={{ $stKey }}" 
                           class="nav-link py-2 px-3 text-start d-flex align-items-center justify-content-between gap-2 border {{ $isTabActive ? 'active fw-bold text-white shadow-sm' : 'text-dark hover-primary' }}"
                           style="border-radius: 10px; {{ $isTabActive ? 'background: linear-gradient(135deg, #2563EB, #1D4ED8); border-color: #2563EB !important;' : 'background: #F8FAFC;' }}; font-size: 0.82rem;">
                            <span class="d-flex align-items-center gap-2">
                                <i class="fas {{ $stData['icon'] }} {{ $isTabActive ? 'text-white' : 'text-primary' }}"></i>
                                <span>{{ $stageNumberLabels[$stKey] }}</span>
                            </span>
                            <span class="badge {{ $isTabActive ? 'bg-white text-primary' : $badgeBg }} rounded-pill ms-2" style="font-size: 0.65rem;">
                                {{ $badgeTxt }}
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- ==================== KONTEN TAHAPAN AKTIF ==================== --}}

    @if($activeTab === 'pemberitahuan')
        {{-- ==================== TAHAP 1: PEMBERITAHUAN ==================== --}}
        <div class="card card-outline card-primary shadow-sm mb-4 border-0">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="card-title fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <span class="rounded-circle bg-primary-subtle text-primary p-2 d-inline-flex align-items-center justify-content-center" style="width:34px; height:34px;">
                        <i class="fas fa-envelope fs-6"></i>
                    </span>
                    Tahap 1: Pemberitahuan
                </h5>
                <div>
                    @if($pemberitahuanRecord && $pemberitahuanRecord->status === 'selesai')
                        <span class="badge bg-success px-3 py-1 rounded-pill"><i class="fas fa-check-circle me-1"></i> Selesai</span>
                    @elseif($pemberitahuanRecord && $pemberitahuanRecord->status === 'skip')
                        <span class="badge bg-secondary px-3 py-1 rounded-pill"><i class="fas fa-forward me-1"></i> Dilewati Secara Manual (Skip)</span>
                    @else
                        <span class="badge bg-primary px-3 py-1 rounded-pill"><i class="fas fa-clock me-1"></i> Tahap Berjalan</span>
                    @endif
                </div>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info border-0 d-flex align-items-center mb-4 rounded-3">
                    <i class="fas fa-circle-info fs-3 text-primary me-3"></i>
                    <div>
                        <div class="fw-bold text-dark">Ketentuan Tahap Pemberitahuan</div>
                        <div class="small text-muted">
                            Pengunggahan dokumen Surat Pemberitahuan pada tahap ini bersifat <strong>opsional</strong>. Jika surat pemberitahuan telah dibuat atau dikirimkan secara manual di luar sistem, Anda dapat langsung menekan tombol <strong>Lewati Tahap (Skip)</strong> untuk melanjutkan ke tahap Pengawasan.
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-7">
                        <div class="border rounded-3 p-3 bg-white h-100 shadow-xs">
                            <h6 class="fw-bold text-dark mb-1">Dokumen Surat Pemberitahuan <span class="badge bg-light text-secondary border ms-1">Opsional (1 File)</span></h6>
                            <p class="text-muted small mb-3">Surat pemberitahuan rencana pengawasan kepada pelaku usaha.</p>

                            @if($pemberitahuanFile)
                                <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-3 {{ $pemberitahuanFile->isLink() ? 'bg-success-subtle text-success' : 'bg-primary-subtle text-primary' }} p-2">
                                            <i class="{{ $pemberitahuanFile->icon_class }} fs-4"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark text-truncate" style="max-width: 320px;">
                                                @if($pemberitahuanFile->isLink())
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle me-1"><i class="fab fa-google-drive me-1"></i>Drive</span>
                                                @endif
                                                {{ $pemberitahuanFile->file_name ?? $pemberitahuanFile->nama_dokumen }}
                                            </div>
                                            <div class="text-muted" style="font-size: 0.75rem;">
                                                {{ $pemberitahuanFile->isLink() ? 'Tautan disimpan' : 'Diunggah' }} {{ $pemberitahuanFile->created_at->format('d M Y H:i') }}
                                                @if($pemberitahuanFile->isFile() && $pemberitahuanFile->formatted_size) &bull; {{ $pemberitahuanFile->formatted_size }} @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-1">
                                        <a href="{{ $pemberitahuanFile->url }}" target="_blank" class="btn btn-sm {{ $pemberitahuanFile->isLink() ? 'btn-outline-success' : 'btn-outline-primary' }}" title="{{ $pemberitahuanFile->isLink() ? 'Buka Link Google Drive' : 'Buka File' }}">
                                            <i class="{{ $pemberitahuanFile->isLink() ? 'fas fa-external-link-alt' : 'fas fa-eye' }}"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="confirmDeleteFile('{{ route('timeline-tahapan-file.destroy', $pemberitahuanFile->id) }}', 'Surat Pemberitahuan')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <form action="{{ route('timeline-tahapan.upload', $pelakuUsaha->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="tahap" value="pemberitahuan">
                                    <input type="hidden" name="kategori" value="surat_pemberitahuan">
                                    <input type="hidden" name="nama_dokumen" value="Surat Pemberitahuan Pengawasan">

                                    <div class="mb-2">
                                        <div class="d-flex gap-3 mb-2 p-2 bg-light rounded-2 border">
                                            <div class="form-check mb-0">
                                                <input class="form-check-input" type="radio" name="tipe" id="tipePemberitahuanFile" value="file" checked onchange="togglePemberitahuanTipe('file')">
                                                <label class="form-check-label small fw-semibold text-dark" for="tipePemberitahuanFile">
                                                    <i class="fas fa-file-upload text-primary me-1"></i> File
                                                </label>
                                            </div>
                                            <div class="form-check mb-0">
                                                <input class="form-check-input" type="radio" name="tipe" id="tipePemberitahuanLink" value="link" onchange="togglePemberitahuanTipe('link')">
                                                <label class="form-check-label small fw-semibold text-dark" for="tipePemberitahuanLink">
                                                    <i class="fab fa-google-drive text-success me-1"></i> Link Google Drive
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3" id="wrapperPemberitahuanFile">
                                        <label class="form-label small fw-semibold text-dark">Pilih File Surat Pemberitahuan</label>
                                        <input type="file" class="form-control form-control-sm" name="file" id="inputPemberitahuanFile" required>
                                        <div class="form-text small" style="font-size: 0.72rem;">Format: PDF, DOCX, JPG (Maks 50MB)</div>
                                    </div>

                                    <div class="mb-3" id="wrapperPemberitahuanLink" style="display: none;">
                                        <label class="form-label small fw-semibold text-dark">URL Link Google Drive</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white"><i class="fab fa-google-drive text-success"></i></span>
                                            <input type="url" name="link_drive" id="inputPemberitahuanLink" class="form-control" placeholder="https://drive.google.com/...">
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-outline-primary btn-sm fw-semibold">
                                        <i class="fas fa-upload me-1"></i> Simpan Surat Pemberitahuan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="border rounded-3 p-3 bg-light h-100 d-flex flex-column justify-content-between">
                            <div>
                                <h6 class="fw-bold text-dark mb-2">Informasi Jadwal Pengawasan</h6>
                                @if($pelakuUsaha->jadwalPengawasans->isNotEmpty())
                                    @php $jadwal = $pelakuUsaha->jadwalPengawasans->sortByDesc('tanggal_rencana')->first(); @endphp
                                    <div class="small text-secondary">
                                        <div><strong>Rencana Tanggal:</strong> {{ \Carbon\Carbon::parse($jadwal->tanggal_rencana)->translatedFormat('d F Y') }}</div>
                                        <div><strong>Jenis Pengawasan:</strong> {{ $jadwal->jenis_pengawasan ?? 'Rutin' }}</div>
                                        <div><strong>Tim Pengawas:</strong> {{ $jadwal->tim_pengawas ?? '-' }}</div>
                                    </div>
                                @else
                                    <p class="small text-muted mb-0">Belum ada agenda jadwal pengawasan tercatat.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Bar Bawah Pemberitahuan --}}
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        @if($pemberitahuanRecord && in_array($pemberitahuanRecord->status, ['selesai', 'skip']))
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill small">
                                <i class="fas fa-check-circle me-1"></i> Data Pemberitahuan Telah Selesai / Dilewati
                            </span>
                        @endif
                        {{-- Tombol Skip --}}
                        <form action="{{ route('timeline-tahapan.skip', $pelakuUsaha->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="tahap" value="pemberitahuan">
                            <button type="submit" class="btn btn-outline-secondary fw-semibold px-3 py-2 rounded-pill shadow-xs" onclick="return confirm('Lewati tahap Pemberitahuan ini secara manual?')">
                                <i class="fas fa-forward me-1"></i> Lewati (Skip)
                            </button>
                        </form>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <a href="?tab=pengawasan" class="btn btn-outline-primary fw-semibold px-3 py-2 rounded-pill shadow-xs">
                            Lihat Tahap Pengawasan (BA) <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                        {{-- Tombol Selesai & Lanjut --}}
                        <form action="{{ route('timeline-tahapan.status', $pelakuUsaha->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="tahap" value="pemberitahuan">
                            <input type="hidden" name="status" value="selesai">
                            <input type="hidden" name="next_tab" value="pengawasan">
                            <button type="submit" class="btn btn-primary fw-bold px-4 py-2 rounded-pill shadow-sm">
                                Simpan Selesai & Lanjut <i class="fas fa-arrow-right ms-1"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    @elseif($activeTab === 'pengawasan')
        {{-- ==================== TAHAP 2: PENGAWASAN (BERITA ACARA) ==================== --}}
        @php
            $allBas = collect();
            foreach (\App\Services\TimelinePengawasanService::BA_TYPES as $rel => $meta) {
                foreach ($pelakuUsaha->{$rel} ?? [] as $baItem) {
                    $allBas->push((object) [
                        'label' => $meta['label'],
                        'color' => $meta['color'],
                        'nomor_ba' => $baItem->nomor_ba ?: 'Tanpa nomor',
                        'tanggal' => $baItem->tanggal_pengawasan ?? $baItem->created_at,
                        'status' => $baItem->status,
                        'url' => route($meta['route'], $baItem->id),
                    ]);
                }
            }
            $pengawasanFiles = $pengawasanRecord ? $pengawasanRecord->files : collect();
        @endphp

        <div class="card card-outline card-primary shadow-sm mb-4 border-0">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="card-title fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <span class="rounded-circle bg-primary-subtle text-primary p-2 d-inline-flex align-items-center justify-content-center" style="width:34px; height:34px;">
                        <i class="fas fa-eye fs-6"></i>
                    </span>
                    Tahap 2: Pengawasan (Berita Acara Pengawasan)
                </h5>
                <div>
                    @if($pengawasanRecord && $pengawasanRecord->status === 'selesai')
                        <span class="badge bg-success px-3 py-1 rounded-pill"><i class="fas fa-check-circle me-1"></i> Selesai</span>
                    @elseif($pengawasanRecord && $pengawasanRecord->status === 'skip')
                        <span class="badge bg-secondary px-3 py-1 rounded-pill"><i class="fas fa-forward me-1"></i> Dilewati Secara Manual (Skip)</span>
                    @else
                        <span class="badge bg-primary px-3 py-1 rounded-pill"><i class="fas fa-clock me-1"></i> Tahap Berjalan</span>
                    @endif
                </div>
            </div>
            <div class="card-body p-4">
                <p class="text-muted small mb-3">
                    Pelaksanaan pengawasan lapangan dan penerbitan Berita Acara (BA) resmi untuk pelaku usaha <strong>{{ $pelakuUsaha->nama_perusahaan }}</strong>.
                </p>

                {{-- Daftar Berita Acara (BA) Terkait --}}
                <div class="card border rounded-3 mb-4 shadow-none">
                    <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-dark mb-0"><i class="fas fa-file-contract text-primary me-2"></i>Berita Acara (BA) Terdaftar di Sistem</h6>
                        <span class="badge bg-primary">{{ $allBas->count() }} Berita Acara</span>
                    </div>
                    <div class="card-body p-0">
                        @if($allBas->isEmpty())
                            <div class="text-center py-4 text-muted small">
                                <i class="fas fa-file-circle-question fs-3 mb-2 d-block text-secondary opacity-50"></i>
                                Belum ada Berita Acara (BA) tersimpan untuk pelaku usaha ini. Anda dapat membuat BA di menu Pengawasan atau mengunggah berkas scan BA di bawah.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover table-sm align-middle mb-0">
                                    <thead class="table-light small">
                                        <tr>
                                            <th class="ps-3">Jenis Berita Acara</th>
                                            <th>Nomor BA</th>
                                            <th>Tanggal Pengawasan</th>
                                            <th>Status</th>
                                            <th class="text-center" style="width: 100px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allBas as $b)
                                        <tr>
                                            <td class="ps-3 fw-semibold"><span class="badge bg-{{ $b->color }}">{{ $b->label }}</span></td>
                                            <td>{{ $b->nomor_ba }}</td>
                                            <td class="small text-muted">{{ \Carbon\Carbon::parse($b->tanggal)->translatedFormat('d M Y') }}</td>
                                            <td><span class="badge bg-light text-dark border">{{ ucfirst($b->status ?? 'Selesai') }}</span></td>
                                            <td class="text-center">
                                                <a href="{{ $b->url }}" class="btn btn-sm btn-outline-primary" target="_blank" title="Buka BA"><i class="fas fa-eye"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Berkas BA Tambahan / Terunggah Langsung --}}
                @if($allBas->isNotEmpty())
                    {{-- Sudah tersinkron dengan BA di menu Pengawasan: tidak perlu upload ulang --}}
                    <div class="alert alert-success border-0 rounded-3 d-flex align-items-start gap-2 mb-4">
                        <i class="fas fa-circle-check mt-1"></i>
                        <div class="small">
                            <strong>Berita Acara sudah tersinkron dari menu Pengawasan.</strong>
                            Tidak perlu mengunggah dokumen BA lagi. Untuk mengubah atau melengkapi dokumen, buka BA melalui tombol
                            <i class="fas fa-eye"></i> pada tabel di atas.
                        </div>
                    </div>
                @endif
                @if($allBas->isEmpty() || $pengawasanFiles->isNotEmpty())
                <div class="border rounded-3 p-3 bg-light mb-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <div>
                            <h6 class="fw-bold text-dark mb-0"><i class="fas fa-paperclip text-primary me-2"></i>Berkas Dokumen BA Pengawasan (Upload Langsung)</h6>
                            @if($allBas->isEmpty())
                                <small class="text-muted">Belum ada BA di menu Pengawasan. Pilih jenis pengawasan (PRL, ALSE, Reklamasi, PPK, Pencemaran) agar otomatis tersimpan &amp; bertambah di data pengawasan terkait.</small>
                            @endif
                        </div>
                        @if($allBas->isEmpty())
                            <button type="button" id="btn-upload-ba" class="btn btn-sm btn-primary fw-semibold px-3 py-2 rounded-pill shadow-xs" onclick="openUploadBaModal()">
                                <i class="fas fa-file-circle-plus me-1"></i> Upload Dokumen BA
                            </button>
                        @endif
                    </div>
                    @if($pengawasanFiles->isEmpty())
                        <div class="text-muted small py-2">Belum ada file scan BA tambahan yang diunggah.</div>
                    @else
                        <div class="table-responsive border rounded-2 bg-white">
                            <table class="table table-sm table-hover mb-0">
                                <tbody>
                                    @foreach($pengawasanFiles as $pf)
                                    <tr>
                                        <td class="ps-3">
                                            <i class="{{ $pf->icon_class }} me-2"></i>{{ $pf->nama_dokumen }}
                                            @if($pf->isLink())
                                                <span class="badge bg-success-subtle text-success border border-success-subtle ms-1" style="font-size: 0.7rem;"><i class="fab fa-google-drive me-1"></i>Drive</span>
                                            @endif
                                        </td>
                                        <td class="small text-muted">{{ $pf->isLink() ? 'Tautan Google Drive' : ($pf->file_name ?? '-') }}</td>
                                        <td class="small text-muted">{{ $pf->created_at->format('d M Y H:i') }}</td>
                                        <td class="text-end pe-3">
                                            <a href="{{ $pf->url }}" target="_blank" class="btn btn-sm btn-light {{ $pf->isLink() ? 'text-success' : 'text-primary' }}" title="{{ $pf->isLink() ? 'Buka Link Google Drive' : 'Unduh File' }}">
                                                <i class="{{ $pf->isLink() ? 'fas fa-external-link-alt' : 'fas fa-download' }}"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-light text-danger" onclick="confirmDeleteFile('{{ route('timeline-tahapan-file.destroy', $pf->id) }}', '{{ $pf->nama_dokumen }}')"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                @endif

                {{-- Action Bar Bawah Pengawasan --}}
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <a href="?tab=pemberitahuan" class="btn btn-outline-secondary fw-semibold px-3 py-2 rounded-pill shadow-xs">
                            <i class="fas fa-arrow-left me-1"></i> Tinjau Pemberitahuan
                        </a>
                        {{-- Tombol Skip --}}
                        <form action="{{ route('timeline-tahapan.skip', $pelakuUsaha->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="tahap" value="pengawasan">
                            <button type="submit" class="btn btn-outline-secondary fw-semibold px-3 py-2 rounded-pill shadow-xs" onclick="return confirm('Lewati tahap Pengawasan ini secara manual?')">
                                <i class="fas fa-forward me-1"></i> Lewati (Skip)
                            </button>
                        </form>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <a href="?tab=saran" class="btn btn-outline-primary fw-semibold px-3 py-2 rounded-pill shadow-xs">
                            Lihat Saran <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                        {{-- Tombol Lanjut ke Saran / Tindak Lanjut --}}
                        <form action="{{ route('timeline-tahapan.status', $pelakuUsaha->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="tahap" value="pengawasan">
                            <input type="hidden" name="status" value="selesai">
                            <input type="hidden" name="next_tab" value="saran">
                            <button type="submit" class="btn btn-primary fw-bold px-4 py-2 rounded-pill shadow-sm">
                                Simpan Selesai & Lanjut <i class="fas fa-arrow-right ms-1"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    @elseif($activeTab === 'saran')
        {{-- ==================== TAHAP 3: SARAN / TINDAK LANJUT ==================== --}}
        @php
            $saranFiles = $saranRecord ? $saranRecord->files : collect();
        @endphp
        <div class="card card-outline card-primary shadow-sm mb-4 border-0">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="card-title fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <span class="rounded-circle bg-primary-subtle text-primary p-2 d-inline-flex align-items-center justify-content-center" style="width:34px; height:34px;">
                        <i class="fas fa-file-signature fs-6"></i>
                    </span>
                    Tahap 3: Saran / Tindak Lanjut
                </h5>
                <div>
                    @if($saranRecord && $saranRecord->status === 'selesai')
                        <span class="badge bg-success px-3 py-1 rounded-pill"><i class="fas fa-check-circle me-1"></i> Selesai</span>
                    @elseif($saranRecord && $saranRecord->status === 'skip')
                        <span class="badge bg-secondary px-3 py-1 rounded-pill"><i class="fas fa-forward me-1"></i> Dilewati Secara Manual (Skip)</span>
                    @else
                        <span class="badge bg-primary px-3 py-1 rounded-pill"><i class="fas fa-clock me-1"></i> Tahap Berjalan</span>
                    @endif
                </div>
            </div>
            <div class="card-body p-4">
                <p class="text-muted small mb-3">
                    Pemberian rekomendasi tindak lanjut, peringatan, atau hasil evaluasi pengawasan untuk pelaku usaha <strong>{{ $pelakuUsaha->nama_perusahaan }}</strong>.
                </p>

                @if($pelakuUsaha->suratPeringatans->isNotEmpty())
                    <div class="card border rounded-3 mb-4 shadow-none">
                        <div class="card-header bg-light py-2">
                            <h6 class="fw-bold text-dark mb-0"><i class="fas fa-triangle-exclamation text-warning me-2"></i>Catatan Surat Peringatan / Sanksi</h6>
                        </div>
                        <div class="card-body p-3 small">
                            @foreach($pelakuUsaha->suratPeringatans as $sp)
                                <div class="mb-2 pb-2 border-bottom">
                                    <strong>No. KKPRL:</strong> {{ $sp->nomor_kkprl ?? '-' }} &bull; 
                                    <strong>Status:</strong> <span class="badge bg-danger">{{ $sp->sanksi ?? 'Tindak Lanjut' }}</span>
                                    <div class="text-muted mt-1">{{ $sp->detil_kegiatan ?? 'Tanpa catatan detil' }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="border rounded-3 p-3 bg-light mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0"><i class="fas fa-paperclip text-primary me-2"></i>Berkas Dokumen Saran & Tindak Lanjut</h6>
                        <button type="button" class="btn btn-sm btn-primary" onclick="openUploadModal('saran', 'dokumen_saran', 'Dokumen Saran & Tindak Lanjut')">
                            <i class="fas fa-upload me-1"></i> Upload Dokumen Saran
                        </button>
                    </div>
                    @if($saranFiles->isEmpty())
                        <div class="text-muted small py-2">Belum ada file dokumen saran yang diunggah.</div>
                    @else
                        <div class="table-responsive border rounded-2 bg-white">
                            <table class="table table-sm table-hover mb-0">
                                <tbody>
                                    @foreach($saranFiles as $sf)
                                    <tr>
                                        <td class="ps-3">
                                            <i class="{{ $sf->icon_class }} me-2"></i>{{ $sf->nama_dokumen }}
                                            @if($sf->isLink())
                                                <span class="badge bg-success-subtle text-success border border-success-subtle ms-1" style="font-size: 0.7rem;"><i class="fab fa-google-drive me-1"></i>Drive</span>
                                            @endif
                                        </td>
                                        <td class="small text-muted">{{ $sf->isLink() ? 'Tautan Google Drive' : ($sf->file_name ?? '-') }}</td>
                                        <td class="small text-muted">{{ $sf->created_at->format('d M Y H:i') }}</td>
                                        <td class="text-end pe-3">
                                            <a href="{{ $sf->url }}" target="_blank" class="btn btn-sm btn-light {{ $sf->isLink() ? 'text-success' : 'text-primary' }}" title="{{ $sf->isLink() ? 'Buka Link Google Drive' : 'Unduh File' }}">
                                                <i class="{{ $sf->isLink() ? 'fas fa-external-link-alt' : 'fas fa-download' }}"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-light text-danger" onclick="confirmDeleteFile('{{ route('timeline-tahapan-file.destroy', $sf->id) }}', '{{ $sf->nama_dokumen }}')"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                {{-- Action Bar Bawah Saran --}}
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <a href="?tab=pengawasan" class="btn btn-outline-secondary fw-semibold px-3 py-2 rounded-pill shadow-xs">
                            <i class="fas fa-arrow-left me-1"></i> Tinjau Pengawasan (BA)
                        </a>
                        <form action="{{ route('timeline-tahapan.skip', $pelakuUsaha->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="tahap" value="saran">
                            <button type="submit" class="btn btn-outline-secondary fw-semibold px-3 py-2 rounded-pill shadow-xs" onclick="return confirm('Lewati tahap Saran / Tindak Lanjut ini secara manual?')">
                                <i class="fas fa-forward me-1"></i> Lewati (Skip)
                            </button>
                        </form>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <a href="?tab=keterangan" class="btn btn-outline-primary fw-semibold px-3 py-2 rounded-pill shadow-xs">
                            Lihat Keterangan <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                        <form action="{{ route('timeline-tahapan.status', $pelakuUsaha->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="tahap" value="saran">
                            <input type="hidden" name="status" value="selesai">
                            <input type="hidden" name="next_tab" value="keterangan">
                            <button type="submit" class="btn btn-primary fw-bold px-4 py-2 rounded-pill shadow-sm">
                                Simpan Selesai & Lanjut <i class="fas fa-arrow-right ms-1"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    @elseif($activeTab === 'keterangan')
        {{-- ==================== TAHAP 4: PEMERIKSAAN KETERANGAN ==================== --}}
        @php
            $keteranganFiles = $keteranganRecord ? $keteranganRecord->files : collect();
        @endphp
        <div class="card card-outline card-primary shadow-sm mb-4 border-0">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="card-title fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <span class="rounded-circle bg-primary-subtle text-primary p-2 d-inline-flex align-items-center justify-content-center" style="width:34px; height:34px;">
                        <i class="fas fa-file-contract fs-6"></i>
                    </span>
                    Tahap 4: Pemeriksaan Keterangan
                </h5>
                <div>
                    @if($keteranganRecord && $keteranganRecord->status === 'selesai')
                        <span class="badge bg-success px-3 py-1 rounded-pill"><i class="fas fa-check-circle me-1"></i> Selesai</span>
                    @elseif($keteranganRecord && $keteranganRecord->status === 'skip')
                        <span class="badge bg-secondary px-3 py-1 rounded-pill"><i class="fas fa-forward me-1"></i> Dilewati Secara Manual (Skip)</span>
                    @else
                        <span class="badge bg-primary px-3 py-1 rounded-pill"><i class="fas fa-clock me-1"></i> Tahap Berjalan</span>
                    @endif
                </div>
            </div>
            <div class="card-body p-4">
                <p class="text-muted small mb-3">
                    Pemeriksaan keterangan, klarifikasi, atau telaah lanjutan terhadap dokumen dan fakta lapangan dari pelaku usaha <strong>{{ $pelakuUsaha->nama_perusahaan }}</strong>.
                </p>

                <div class="border rounded-3 p-3 bg-light mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0"><i class="fas fa-paperclip text-primary me-2"></i>Dokumen Pemeriksaan Keterangan</h6>
                        <button type="button" class="btn btn-sm btn-primary" onclick="openUploadModal('keterangan', 'dokumen_keterangan', 'BA Pemeriksaan Keterangan')">
                            <i class="fas fa-upload me-1"></i> Upload Dokumen Keterangan
                        </button>
                    </div>
                    @if($keteranganFiles->isEmpty())
                        <div class="text-muted small py-2">Belum ada dokumen pemeriksaan keterangan yang diunggah.</div>
                    @else
                        <div class="table-responsive border rounded-2 bg-white">
                            <table class="table table-sm table-hover mb-0">
                                <tbody>
                                    @foreach($keteranganFiles as $kf)
                                    <tr>
                                        <td class="ps-3">
                                            <i class="{{ $kf->icon_class }} me-2"></i>{{ $kf->nama_dokumen }}
                                            @if($kf->isLink())
                                                <span class="badge bg-success-subtle text-success border border-success-subtle ms-1" style="font-size: 0.7rem;"><i class="fab fa-google-drive me-1"></i>Drive</span>
                                            @endif
                                        </td>
                                        <td class="small text-muted">{{ $kf->isLink() ? 'Tautan Google Drive' : ($kf->file_name ?? '-') }}</td>
                                        <td class="small text-muted">{{ $kf->created_at->format('d M Y H:i') }}</td>
                                        <td class="text-end pe-3">
                                            <a href="{{ $kf->url }}" target="_blank" class="btn btn-sm btn-light {{ $kf->isLink() ? 'text-success' : 'text-primary' }}" title="{{ $kf->isLink() ? 'Buka Link Google Drive' : 'Unduh File' }}">
                                                <i class="{{ $kf->isLink() ? 'fas fa-external-link-alt' : 'fas fa-download' }}"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-light text-danger" onclick="confirmDeleteFile('{{ route('timeline-tahapan-file.destroy', $kf->id) }}', '{{ $kf->nama_dokumen }}')"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                {{-- Action Bar Bawah Keterangan --}}
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <a href="?tab=saran" class="btn btn-outline-secondary fw-semibold px-3 py-2 rounded-pill shadow-xs">
                            <i class="fas fa-arrow-left me-1"></i> Tinjau Saran / Tindak Lanjut
                        </a>
                        <form action="{{ route('timeline-tahapan.skip', $pelakuUsaha->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="tahap" value="keterangan">
                            <button type="submit" class="btn btn-outline-secondary fw-semibold px-3 py-2 rounded-pill shadow-xs" onclick="return confirm('Lewati tahap Pemeriksaan Keterangan ini secara manual?')">
                                <i class="fas fa-forward me-1"></i> Lewati (Skip)
                            </button>
                        </form>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <a href="?tab=supervisi" class="btn btn-outline-primary fw-semibold px-3 py-2 rounded-pill shadow-xs">
                            Lihat Supervisi <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                        <form action="{{ route('timeline-tahapan.status', $pelakuUsaha->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="tahap" value="keterangan">
                            <input type="hidden" name="status" value="selesai">
                            <input type="hidden" name="next_tab" value="supervisi">
                            <button type="submit" class="btn btn-primary fw-bold px-4 py-2 rounded-pill shadow-sm">
                                Simpan Selesai & Lanjut <i class="fas fa-arrow-right ms-1"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    @elseif($activeTab === 'supervisi')
        {{-- ==================== TAHAP 5: SUPERVISI ==================== --}}
        
        {{-- 1. Dokumen Utama Supervisi --}}
        <div class="card card-outline card-primary shadow-sm mb-4 border-0">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h5 class="card-title fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <span class="rounded-circle bg-primary-subtle text-primary p-2 d-inline-flex align-items-center justify-content-center" style="width:34px; height:34px;">
                        <i class="fas fa-shield-halved fs-6"></i>
                    </span>
                    Dokumen Utama Supervisi
                </h5>
                @if($isSupervisiDone)
                    <span class="badge bg-success px-3 py-1 rounded-pill">
                        <i class="fas fa-check-circle me-1"></i> Supervisi Selesai
                    </span>
                @else
                    <span class="badge bg-primary px-3 py-1 rounded-pill">
                        <i class="fas fa-clock me-1"></i> Dalam Proses
                    </span>
                @endif
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    {{-- Bahan Paparan --}}
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 bg-white h-100 d-flex flex-column justify-content-between shadow-xs">
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-3 {{ $bahanPaparan && $bahanPaparan->isLink() ? 'bg-success-subtle text-success' : 'bg-primary-subtle text-primary' }} p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                        <i class="{{ $bahanPaparan ? $bahanPaparan->icon_class : 'fas fa-file-powerpoint' }} fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1">Bahan Paparan</h6>
                                        @if($bahanPaparan)
                                            <div class="small text-muted text-truncate" style="max-width: 260px;" title="{{ $bahanPaparan->file_name ?? $bahanPaparan->nama_dokumen }}">
                                                @if($bahanPaparan->isLink())
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle me-1"><i class="fab fa-google-drive me-1"></i>Drive</span>
                                                @else
                                                    <i class="fas fa-check-circle text-success me-1"></i>
                                                @endif
                                                <span class="fw-semibold text-dark">{{ $bahanPaparan->file_name ?? $bahanPaparan->nama_dokumen }}</span>
                                                @if($bahanPaparan->isFile() && $bahanPaparan->formatted_size)
                                                    <span class="text-secondary">({{ $bahanPaparan->formatted_size }})</span>
                                                @endif
                                            </div>
                                            <div class="text-muted" style="font-size: 0.72rem;">
                                                {{ $bahanPaparan->isLink() ? 'Tautan disimpan' : 'Diunggah' }} {{ $bahanPaparan->created_at->format('d M Y H:i') }}
                                            </div>
                                        @else
                                            <div class="small text-muted">Belum ada file</div>
                                        @endif
                                    </div>
                                </div>
                                @if($bahanPaparan)
                                    <div class="d-flex gap-1">
                                        <a href="{{ $bahanPaparan->url }}" target="_blank" class="btn btn-sm {{ $bahanPaparan->isLink() ? 'btn-outline-success' : 'btn-outline-primary' }}" title="{{ $bahanPaparan->isLink() ? 'Buka Link Google Drive' : 'Lihat/Unduh File' }}">
                                            <i class="{{ $bahanPaparan->isLink() ? 'fas fa-external-link-alt' : 'fas fa-eye' }}"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus Dokumen" onclick="confirmDeleteFile('{{ route('timeline-tahapan-file.destroy', $bahanPaparan->id) }}', 'Bahan Paparan')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div class="border-top pt-2 mt-2">
                                <button type="button" class="btn btn-light w-100 text-primary fw-semibold py-2 d-flex align-items-center justify-content-center gap-2 rounded-2" onclick="openUploadModal('supervisi', 'bahan_paparan', 'Bahan Paparan')">
                                    <i class="fas fa-arrow-up-from-bracket"></i> {{ $bahanPaparan ? 'Ganti File / Link' : 'Upload File / Link Drive' }}
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Permohonan Supervisi --}}
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 bg-white h-100 d-flex flex-column justify-content-between shadow-xs">
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-3 {{ $permohonanSupervisi && $permohonanSupervisi->isLink() ? 'bg-success-subtle text-success' : 'bg-primary-subtle text-primary' }} p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                        <i class="{{ $permohonanSupervisi ? $permohonanSupervisi->icon_class : 'fas fa-file-contract' }} fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1">Permohonan Supervisi</h6>
                                        @if($permohonanSupervisi)
                                            <div class="small text-muted text-truncate" style="max-width: 260px;" title="{{ $permohonanSupervisi->file_name ?? $permohonanSupervisi->nama_dokumen }}">
                                                @if($permohonanSupervisi->isLink())
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle me-1"><i class="fab fa-google-drive me-1"></i>Drive</span>
                                                @else
                                                    <i class="fas fa-check-circle text-success me-1"></i>
                                                @endif
                                                <span class="fw-semibold text-dark">{{ $permohonanSupervisi->file_name ?? $permohonanSupervisi->nama_dokumen }}</span>
                                                @if($permohonanSupervisi->isFile() && $permohonanSupervisi->formatted_size)
                                                    <span class="text-secondary">({{ $permohonanSupervisi->formatted_size }})</span>
                                                @endif
                                            </div>
                                            <div class="text-muted" style="font-size: 0.72rem;">
                                                {{ $permohonanSupervisi->isLink() ? 'Tautan disimpan' : 'Diunggah' }} {{ $permohonanSupervisi->created_at->format('d M Y H:i') }}
                                            </div>
                                        @else
                                            <div class="small text-muted">Belum ada file</div>
                                        @endif
                                    </div>
                                </div>
                                @if($permohonanSupervisi)
                                    <div class="d-flex gap-1">
                                        <a href="{{ $permohonanSupervisi->url }}" target="_blank" class="btn btn-sm {{ $permohonanSupervisi->isLink() ? 'btn-outline-success' : 'btn-outline-primary' }}" title="{{ $permohonanSupervisi->isLink() ? 'Buka Link Google Drive' : 'Lihat/Unduh File' }}">
                                            <i class="{{ $permohonanSupervisi->isLink() ? 'fas fa-external-link-alt' : 'fas fa-eye' }}"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus Dokumen" onclick="confirmDeleteFile('{{ route('timeline-tahapan-file.destroy', $permohonanSupervisi->id) }}', 'Permohonan Supervisi')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div class="border-top pt-2 mt-2">
                                <button type="button" class="btn btn-light w-100 text-primary fw-semibold py-2 d-flex align-items-center justify-content-center gap-2 rounded-2" onclick="openUploadModal('supervisi', 'permohonan_supervisi', 'Permohonan Supervisi')">
                                    <i class="fas fa-arrow-up-from-bracket"></i> {{ $permohonanSupervisi ? 'Ganti File / Link' : 'Upload File / Link Drive' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Dokumen Pelaku Usaha (Tersinkronisasi) --}}
        <div class="card card-outline card-primary shadow-sm mb-4 border-0">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h5 class="card-title fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <span class="rounded-circle bg-success-subtle text-success p-2 d-inline-flex align-items-center justify-content-center" style="width:34px; height:34px;">
                        <i class="fas fa-arrows-rotate fs-6"></i>
                    </span>
                    Dokumen Pelaku Usaha (Tersinkronisasi)
                </h5>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill" style="font-size:0.75rem">
                    <i class="fas fa-check-double me-1"></i> Real-time Sync
                </span>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    {{-- NIB & Profil --}}
                    <div class="col-md-4">
                        <div class="border border-secondary-subtle rounded-3 p-3 bg-white h-100 d-flex flex-column justify-content-between shadow-xs">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-success-subtle text-success p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                            <i class="fas fa-paperclip fs-6"></i>
                                        </div>
                                        <div class="fw-bold text-dark fs-6">NIB & Profil</div>
                                    </div>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill" style="font-size: 0.65rem; font-weight: 700;">TER-SYNC</span>
                                </div>
                                <div class="small text-secondary my-2">
                                    <div><strong>NIB:</strong> <span class="text-dark">{{ $company?->nib ?? ($pelakuUsaha->nib ?? 'OSS-TERDAFTAR') }}</span></div>
                                    <div class="text-truncate"><strong>Perusahaan:</strong> <span class="text-dark">{{ $pelakuUsaha->nama_perusahaan }}</span></div>
                                    <div><strong>PIC:</strong> <span class="text-dark">{{ $pelakuUsaha->nama_pic ?? '-' }}</span></div>
                                </div>
                            </div>
                            <div>
                                <div class="d-flex justify-content-between align-items-center text-muted small pt-2 border-top">
                                    <span>Sumber:</span>
                                    <span class="fw-semibold text-dark">OSS / Master Data</span>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-success w-100 mt-2 rounded-2" data-bs-toggle="modal" data-bs-target="#modalDetailNib">
                                    <i class="fas fa-eye me-1"></i> Lihat Data Tersinkron
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Dokumen PKKPRL --}}
                    <div class="col-md-4">
                        <div class="border border-secondary-subtle rounded-3 p-3 bg-white h-100 d-flex flex-column justify-content-between shadow-xs">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-success-subtle text-success p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                            <i class="fas fa-paperclip fs-6"></i>
                                        </div>
                                        <div class="fw-bold text-dark fs-6">Dokumen PKKPRL</div>
                                    </div>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill" style="font-size: 0.65rem; font-weight: 700;">TER-SYNC</span>
                                </div>
                                <div class="small text-secondary my-2">
                                    <div class="text-truncate"><strong>No. PKKPRL:</strong> <span class="text-dark">{{ $pelakuUsaha->nomor_pkkprl ?? '-' }}</span></div>
                                    <div><strong>Luas:</strong> <span class="text-dark">{{ $pelakuUsaha->luas_pkkprl ? number_format($pelakuUsaha->luas_pkkprl, 2) . ' m²' : '-' }}</span></div>
                                    <div><strong>Jenis Usaha:</strong> <span class="text-dark">{{ $pelakuUsaha->jenisUsaha?->nama ?? '-' }}</span></div>
                                </div>
                            </div>
                            <div>
                                <div class="d-flex justify-content-between align-items-center text-muted small pt-2 border-top">
                                    <span>Sumber:</span>
                                    <span class="fw-semibold text-dark">Sistem KKP</span>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-success w-100 mt-2 rounded-2" data-bs-toggle="modal" data-bs-target="#modalDetailPkkprl">
                                    <i class="fas fa-eye me-1"></i> Lihat Data Tersinkron
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Persyaratan Teknis --}}
                    @php
                        $totalTeknis = $pelakuUsaha->dokumenPelakuUsahas->count() + $pelakuUsaha->dokumens->count();
                    @endphp
                    <div class="col-md-4">
                        <div class="border border-secondary-subtle rounded-3 p-3 bg-white h-100 d-flex flex-column justify-content-between shadow-xs">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-success-subtle text-success p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                            <i class="fas fa-paperclip fs-6"></i>
                                        </div>
                                        <div class="fw-bold text-dark fs-6">Persyaratan Teknis</div>
                                    </div>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill" style="font-size: 0.65rem; font-weight: 700;">TER-SYNC</span>
                                </div>
                                <div class="small text-secondary my-2">
                                    <div><strong>Total Berkas:</strong> <span class="badge bg-primary text-white">{{ $totalTeknis }} Berkas</span></div>
                                    <div><strong>Status:</strong> <span class="text-success fw-semibold"><i class="fas fa-check-circle me-1"></i>Tersedia & Terverifikasi</span></div>
                                    <div class="text-muted" style="font-size: 0.72rem;">Dokumen perizinan & berkas pelaku usaha</div>
                                </div>
                            </div>
                            <div>
                                <div class="d-flex justify-content-between align-items-center text-muted small pt-2 border-top">
                                    <span>Sumber:</span>
                                    <span class="fw-semibold text-dark">Upload Pelaku Usaha</span>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-success w-100 mt-2 rounded-2" data-bs-toggle="modal" data-bs-target="#modalDetailTeknis">
                                    <i class="fas fa-eye me-1"></i> Lihat Berkas Teknis ({{ $totalTeknis }})
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Peta Spasial (Maks 8 Foto) --}}
        <div class="card card-outline card-primary shadow-sm mb-4 border-0">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="card-title fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <span class="rounded-circle bg-primary-subtle text-primary p-2 d-inline-flex align-items-center justify-content-center" style="width:34px; height:34px;">
                        <i class="fas fa-map-location-dot fs-6"></i>
                    </span>
                    Peta Spasial <span class="fw-normal text-muted" style="font-size: 0.9rem;">(Maks 8 Foto)</span>
                </h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary fw-semibold" onclick="$('#modalBulkUpload').modal('show')">
                        <i class="fas fa-images me-1"></i> Upload Banyak Sekaligus
                    </button>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row row-cols-2 row-cols-sm-4 row-cols-md-4 row-cols-lg-8 g-3">
                    @for($s = 1; $s <= 8; $s++)
                        @php
                            $foto = $petaSpasials->firstWhere('slot', $s);
                        @endphp
                        <div class="col">
                            @if($foto)
                                <div class="position-relative rounded-3 border overflow-hidden shadow-xs {{ $foto->isLink() ? 'bg-light' : 'bg-dark' }}" style="height: 110px;">
                                    @if($foto->isLink())
                                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-center p-2">
                                            <i class="fab fa-google-drive text-success fs-3 mb-1"></i>
                                            <div class="small fw-semibold text-truncate w-100 text-dark" style="font-size: 0.65rem;">Google Drive</div>
                                        </div>
                                    @else
                                        <img src="{{ asset('storage/' . $foto->file_path) }}" alt="Foto {{ $s }}" class="w-100 h-100 object-fit-cover opacity-90" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'" onclick="previewImage('{{ asset('storage/' . $foto->file_path) }}', 'Peta Spasial - Foto {{ $s }}')">
                                    @endif
                                    <span class="badge {{ $foto->isLink() ? 'bg-success' : 'bg-dark bg-opacity-75' }} position-absolute top-0 start-0 m-1" style="font-size: 0.65rem;">Foto {{ $s }}</span>
                                    <div class="position-absolute bottom-0 end-0 m-1 d-flex gap-1">
                                        @if($foto->isLink())
                                            <a href="{{ $foto->link_drive }}" target="_blank" class="btn btn-light btn-xs p-1 rounded-circle shadow-sm" style="width: 22px; height: 22px; font-size: 0.65rem; line-height: 1;" title="Buka di Drive">
                                                <i class="fas fa-external-link-alt text-success"></i>
                                            </a>
                                        @else
                                            <button type="button" class="btn btn-light btn-xs p-1 rounded-circle shadow-sm" style="width: 22px; height: 22px; font-size: 0.65rem; line-height: 1;" title="Perbesar" onclick="previewImage('{{ asset('storage/' . $foto->file_path) }}', 'Peta Spasial - Foto {{ $s }}')">
                                                <i class="fas fa-search-plus text-primary"></i>
                                            </button>
                                        @endif
                                        <button type="button" class="btn btn-danger btn-xs p-1 rounded-circle shadow-sm" style="width: 22px; height: 22px; font-size: 0.65rem; line-height: 1;" title="Hapus Foto" onclick="confirmDeleteFile('{{ route('timeline-tahapan-file.destroy', $foto->id) }}', 'Foto {{ $s }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="rounded-3 border border-2 border-dashed p-2 text-center bg-light h-100 d-flex flex-column align-items-center justify-content-center" style="min-height: 110px; cursor: pointer; transition: all 0.2s; border-color: #cbd5e1 !important;" onmouseover="this.style.borderColor='#3b82f6'; this.style.backgroundColor='#eff6ff';" onmouseout="this.style.borderColor='#cbd5e1'; this.style.backgroundColor='#f8fafc';" onclick="openUploadModal('supervisi', 'peta_spasial', 'Foto {{ $s }}', {{ $s }})">
                                    <i class="fas fa-camera text-secondary mb-1 fs-5"></i>
                                    <div class="small text-muted" style="font-size: 0.75rem;">Foto {{ $s }}</div>
                                    <span class="badge bg-light text-primary border border-primary-subtle mt-1" style="font-size: 0.6rem;">+ Unggah</span>
                                </div>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        {{-- 4. Dokumen Tambahan --}}
        <div class="card card-outline card-primary shadow-sm mb-4 border-0">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="card-title fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <span class="rounded-circle bg-primary-subtle text-primary p-2 d-inline-flex align-items-center justify-content-center" style="width:34px; height:34px;">
                        <i class="fas fa-paperclip fs-6"></i>
                    </span>
                    Dokumen Tambahan
                </h5>
                <button type="button" class="btn btn-sm btn-primary fw-semibold" onclick="openUploadModal('supervisi', 'dokumen_tambahan', '')">
                    <i class="fas fa-plus me-1"></i> Tambah Dokumen Tambahan
                </button>
            </div>
            <div class="card-body p-4">
                @if($dokumenTambahans->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-file-circle-plus fs-2 mb-2 d-block text-secondary opacity-50"></i>
                        <p class="small mb-0">Belum ada dokumen tambahan terunggah untuk tahap Supervisi ini.</p>
                    </div>
                @else
                    <div class="table-responsive border rounded-3">
                        <table class="table table-hover table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3 py-2">Nama Dokumen</th>
                                    <th>Nama File</th>
                                    <th>Ukuran</th>
                                    <th>Tanggal Upload</th>
                                    <th class="text-center" style="width:120px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dokumenTambahans as $dt)
                                <tr>
                                    <td class="ps-3 fw-semibold text-dark">
                                        <i class="{{ $dt->icon_class }} me-2"></i>{{ $dt->nama_dokumen }}
                                        @if($dt->isLink())
                                            <span class="badge bg-success-subtle text-success border border-success-subtle ms-1" style="font-size: 0.7rem;"><i class="fab fa-google-drive me-1"></i>Drive</span>
                                        @endif
                                    </td>
                                    <td class="small text-muted">{{ $dt->isLink() ? 'Tautan Google Drive' : ($dt->file_name ?? basename($dt->file_path)) }}</td>
                                    <td class="small text-muted">{{ $dt->formatted_size ?: '-' }}</td>
                                    <td class="small text-muted">{{ $dt->created_at->format('d M Y H:i') }}</td>
                                    <td class="text-center">
                                        <a href="{{ $dt->url }}" target="_blank" class="btn btn-sm btn-light {{ $dt->isLink() ? 'text-success' : 'text-primary' }}" title="{{ $dt->isLink() ? 'Buka Link Google Drive' : 'Buka File' }}">
                                            <i class="{{ $dt->isLink() ? 'fas fa-external-link-alt' : 'fas fa-download' }}"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-light text-danger" title="Hapus File" onclick="confirmDeleteFile('{{ route('timeline-tahapan-file.destroy', $dt->id) }}', '{{ $dt->nama_dokumen }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- 5. Tombol Aksi / Penyelesaian Tahap Supervisi & Skip --}}
        <div class="card shadow-sm border-0 mb-5 rounded-3 bg-light">
            <div class="card-body p-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-2">
                    <a href="?tab=keterangan" class="btn btn-outline-secondary fw-semibold px-3 py-2 rounded-pill shadow-xs">
                        <i class="fas fa-arrow-left me-1"></i> Tinjau Keterangan
                    </a>
                    {{-- Tombol Skip Supervisi --}}
                    <form action="{{ route('timeline-tahapan.skip', $pelakuUsaha->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="tahap" value="supervisi">
                        <button type="submit" class="btn btn-outline-secondary fw-semibold px-4 py-2 rounded-pill shadow-xs" onclick="return confirm('Lewati tahap Supervisi ini secara manual?')">
                            <i class="fas fa-forward me-1"></i> Lewati (Skip)
                        </button>
                    </form>
                </div>
                <div class="d-flex align-items-center gap-2">
                    @if($isSupervisiDone)
                        <a href="?tab=ekspose" class="btn btn-primary btn-lg fw-bold px-4 py-2 rounded-pill shadow-sm">
                            Ke Tahap Ekspose <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    @else
                        {{-- Tombol selesaikan supervisi & ekspose sekaligus --}}
                        <form action="{{ route('timeline-tahapan.selesaikan-supervisi', $pelakuUsaha->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg fw-bold px-4 py-2 rounded-pill shadow-sm" onclick="return confirm('Apakah Anda yakin ingin menyelesaikan tahap Supervisi & Ekspose ini dan lanjut?')">
                                <i class="fas fa-check-double me-2"></i> Selesaikan Supervisi & Ekspose
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

    @elseif($activeTab === 'ekspose')
        {{-- ==================== TAHAP 6: EKSPOSE ==================== --}}
        @if(!$isSupervisiDone)
            {{-- Supervisi belum selesai: Ekspose terkunci, namun tetap ada opsi skip --}}
            <div class="card shadow-sm border-0 mb-4 rounded-3 text-center py-5">
                <div class="card-body">
                    <div class="mb-3">
                        <span class="rounded-circle bg-warning-subtle text-warning p-4 d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-lock fs-1"></i>
                        </span>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Tahap Supervisi Belum Selesai</h4>
                    <p class="text-muted mb-4 mx-auto" style="max-width: 520px;">
                        Tahap Ekspose diselaraskan dengan Supervisi. Selesaikan tahap Supervisi terlebih dahulu atau lewati tahap ini jika telah dilaksanakan manual di luar sistem.
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="?tab=supervisi" class="btn btn-primary fw-bold px-4 py-2 rounded-pill shadow-sm">
                            <i class="fas fa-arrow-left me-2"></i> Ke Tahap Supervisi
                        </a>
                        <form action="{{ route('timeline-tahapan.skip', $pelakuUsaha->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="tahap" value="ekspose">
                            <button type="submit" class="btn btn-outline-secondary fw-semibold px-4 py-2 rounded-pill shadow-xs" onclick="return confirm('Lewati tahap Ekspose ini secara manual?')">
                                <i class="fas fa-forward me-1"></i> Lewati Tahap Ekspose (Skip)
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            {{-- Supervisi sudah selesai: Ekspose juga sudah selesai dan HANYA AKAN MUNCUL BUTTON LANJUT KE BILLING --}}
            <div class="card card-outline card-primary shadow-sm mb-4 border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h5 class="card-title fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <span class="rounded-circle bg-primary-subtle text-primary p-2 d-inline-flex align-items-center justify-content-center" style="width:34px; height:34px;">
                            <i class="fas fa-desktop fs-6"></i>
                        </span>
                        Tahap 6: Ekspose
                    </h5>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill">
                        <i class="fas fa-check-circle me-1"></i> Supervisi Telah Selesai
                    </span>
                </div>
                <div class="card-body py-5 text-center">
                    <div class="mb-4">
                        <span class="rounded-circle bg-primary-subtle text-primary p-4 d-inline-flex align-items-center justify-content-center shadow-xs" style="width: 88px; height: 88px;">
                            <i class="fas fa-desktop fs-1"></i>
                        </span>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Ekspose Hasil Supervisi Pengawasan</h4>
                    <p class="text-muted mb-4 mx-auto" style="max-width: 580px;">
                        Tahap Supervisi dan Ekspose untuk <strong>{{ $pelakuUsaha->nama_perusahaan }}</strong> telah lengkap. Silakan lanjutkan langsung ke tahap penerbitan tagihan / Billing.
                    </p>
                    
                    {{-- Sesuai permintaan user: HANYA AKAN MUNCUL BUTTON LANJUT KE BILLING, plus link kecil jika ingin tinjau kembali berkas supervisi --}}
                    <div class="d-flex flex-column align-items-center justify-content-center mt-3 gap-2">
                        <form action="{{ route('timeline-tahapan.lanjut-billing', $pelakuUsaha->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg fw-bold px-5 py-3 shadow rounded-pill d-inline-flex align-items-center gap-3">
                                <i class="fas fa-receipt fs-4"></i>
                                <span class="fs-5">Lanjut ke Billing</span>
                                <i class="fas fa-arrow-right fs-5"></i>
                            </button>
                        </form>
                        <a href="?tab=supervisi" class="btn btn-link text-muted small text-decoration-none mt-1">
                            <i class="fas fa-arrow-left me-1"></i> Tinjau Kembali Berkas Dokumen Supervisi
                        </a>
                    </div>
                </div>
            </div>
        @endif

    @elseif($activeTab === 'billing')
        {{-- ==================== TAHAP 7: BILLING ==================== --}}
        <div class="card card-outline card-primary shadow-sm mb-4 border-0">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h5 class="card-title fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <span class="rounded-circle bg-primary-subtle text-primary p-2 d-inline-flex align-items-center justify-content-center" style="width:34px; height:34px;">
                        <i class="fas fa-receipt fs-6"></i>
                    </span>
                    Tahap 7: Billing (PNBP & Pembayaran)
                </h5>
                @if($isBillingDone)
                    <span class="badge bg-success px-3 py-1 rounded-pill"><i class="fas fa-check-circle me-1"></i> Billing Selesai</span>
                @else
                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill"><i class="fas fa-clock me-1"></i> Menunggu Proses Billing</span>
                @endif
            </div>
            <div class="card-body p-4">
                <div class="row g-4 align-items-center">
                    <div class="col-md-7">
                        <h5 class="fw-bold text-dark mb-2">Informasi Billing Pelaku Usaha</h5>
                        <p class="text-muted small mb-3">
                            Tahapan penagihan Penerimaan Negara Bukan Pajak (PNBP) atau tindak lanjut kewajiban pembayaran dari pelaku usaha <strong>{{ $pelakuUsaha->nama_perusahaan }}</strong>.
                        </p>
                        <div class="border rounded-3 p-3 bg-light">
                            <div class="row g-2 small">
                                <div class="col-sm-4 text-muted">Perusahaan:</div>
                                <div class="col-sm-8 fw-semibold text-dark">{{ $pelakuUsaha->nama_perusahaan }}</div>
                                <div class="col-sm-4 text-muted">Nomor PKKPRL:</div>
                                <div class="col-sm-8 fw-semibold text-dark">{{ $pelakuUsaha->nomor_pkkprl ?? '-' }}</div>
                                <div class="col-sm-4 text-muted">Luas Wilayah:</div>
                                <div class="col-sm-8 fw-semibold text-dark">{{ $pelakuUsaha->luas_pkkprl ? number_format($pelakuUsaha->luas_pkkprl, 2) . ' m²' : '-' }}</div>
                                <div class="col-sm-4 text-muted">Status Billing:</div>
                                <div class="col-sm-8">
                                    @if($isBillingDone)
                                        <span class="badge bg-success">Lunas / Selesai</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Dalam Proses Penagihan</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 text-center border-start ps-md-4">
                        <div class="p-3">
                            <i class="fas fa-file-invoice-dollar text-primary fs-1 mb-3 d-block"></i>
                            <h6 class="fw-bold text-dark mb-1">Status Penyelesaian Billing</h6>
                            <p class="text-muted small mb-3">
                                Tandai tahap Billing selesai jika seluruh tagihan atau bukti setoran telah divalidasi.
                            </p>
                            @if(!$isBillingDone)
                                <div class="d-flex justify-content-center gap-2">
                                    <form action="{{ route('timeline-tahapan.skip', $pelakuUsaha->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="tahap" value="billing">
                                        <button type="submit" class="btn btn-outline-secondary fw-semibold px-3 py-2 rounded-pill shadow-xs" onclick="return confirm('Lewati tahap Billing ini secara manual?')">
                                            <i class="fas fa-forward me-1"></i> Lewati (Skip)
                                        </button>
                                    </form>
                                    <form action="{{ route('timeline-tahapan.status', $pelakuUsaha->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="tahap" value="billing">
                                        <input type="hidden" name="status" value="selesai">
                                        <button type="submit" class="btn btn-success fw-bold px-4 py-2 rounded-pill shadow-sm" onclick="return confirm('Tandai tahap Billing selesai?')">
                                            <i class="fas fa-check-circle me-1"></i> Tandai Selesai
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="badge bg-success-subtle text-success border border-success px-4 py-2 rounded-pill fs-6 mb-3">
                                    <i class="fas fa-check-double me-1"></i> Seluruh Rangkaian Selesai
                                </div>
                            @endif

                            {{-- Navigasi Cepat Meninjau Data & Dokumen Tahap Sebelumnya --}}
                            <div class="mt-4 pt-3 border-top text-center">
                                <span class="small fw-semibold text-muted d-block mb-2">
                                    <i class="fas fa-clock-rotate-left me-1 text-primary"></i> Tinjau Data Tahap Sebelumnya:
                                </span>
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <a href="?tab=supervisi" class="btn btn-sm btn-outline-secondary rounded-pill">
                                        <i class="fas fa-shield-halved me-1"></i> Supervisi
                                    </a>
                                    <a href="?tab=keterangan" class="btn btn-sm btn-outline-secondary rounded-pill">
                                        <i class="fas fa-file-contract me-1"></i> Keterangan
                                    </a>
                                    <a href="?tab=saran" class="btn btn-sm btn-outline-secondary rounded-pill">
                                        <i class="fas fa-file-signature me-1"></i> Saran
                                    </a>
                                    <a href="?tab=pengawasan" class="btn btn-sm btn-outline-secondary rounded-pill">
                                        <i class="fas fa-eye me-1"></i> Berita Acara (BA)
                                    </a>
                                    <a href="?tab=pemberitahuan" class="btn btn-sm btn-outline-secondary rounded-pill">
                                        <i class="fas fa-envelope me-1"></i> Pemberitahuan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- RIWAYAT KEJADIAN KRONOLOGIS --}}
    <div class="row g-4 mt-2">
        <div class="col-lg-8">
            <div class="card card-primary card-outline shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="card-title fw-bold mb-0 text-dark"><i class="fas fa-clock-rotate-left me-2 text-primary"></i>Riwayat Kejadian</h5>
                    <div class="btn-group btn-group-sm" role="group" id="tl-filter">
                        <button type="button" class="btn btn-outline-secondary active" data-stage="">Semua ({{ $events->count() }})</button>
                        @foreach(\App\Services\TimelinePengawasanService::STAGES as $key => $st)
                            @if(($countBy[$key] ?? 0) > 0)
                                <button type="button" class="btn btn-outline-secondary" data-stage="{{ $key }}">{{ $st['label'] }} ({{ $countBy[$key] }})</button>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="card-body" id="tl-events-wrap">
                    <x-timeline-events :events="$events" />
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-primary card-outline shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title fw-bold mb-0 text-dark"><i class="fas fa-list-check me-2 text-primary"></i>Status Tiap Tahap</h5>
                </div>
                <ul class="list-group list-group-flush">
                    @php
                        $stateBadge = [
                            'done' => ['Selesai', 'selesai'],
                            'active' => ['Berjalan', 'proses'],
                            'alert' => ['Perlu Tindakan', 'sp'],
                            'next' => ['Berikutnya', 'default'],
                            'skipped' => ['Dilewati', 'default'],
                            'pending' => ['Belum', 'default'],
                        ];
                    @endphp
                    @foreach($timeline['stages'] as $key => $st)
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas {{ $st['icon'] }} fa-fw text-muted"></i>
                                <div>
                                    <a href="?tab={{ $key }}" class="fw-semibold text-dark text-decoration-none small hover-primary">{{ $st['label'] }}</a>
                                    @if($st['note'])<div class="text-muted" style="font-size:.72rem">{{ $st['note'] }}</div>@endif
                                </div>
                            </div>
                            <span class="tl-badge tl-badge-{{ $stateBadge[$st['state']][1] }}">{{ $stateBadge[$st['state']][0] }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- ==================== MODALS ==================== --}}

{{-- Modal Upload Dokumen Tunggal (Bahan Paparan, Permohonan Supervisi, Dokumen Tambahan, BA, dll) --}}
<div class="modal fade" id="modalUpload" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('timeline-tahapan.upload', $pelakuUsaha->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="tahap" id="modalUploadTahap" value="supervisi">
                <input type="hidden" name="kategori" id="modalUploadKategori" value="">
                <input type="hidden" name="slot" id="modalUploadSlot" value="">
                
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold text-dark" id="modalUploadTitle">Upload Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    {{-- Pilihan Tipe: File atau Link Drive --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Pilihan Sumber Dokumen <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3 p-2 bg-light rounded-3 border">
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="radio" name="tipe" id="modalTipeFile" value="file" checked onchange="toggleModalUploadTipe('file')">
                                <label class="form-check-label fw-semibold small text-dark" for="modalTipeFile">
                                    <i class="fas fa-file-arrow-up text-primary me-1"></i> Unggah File
                                </label>
                            </div>
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="radio" name="tipe" id="modalTipeLink" value="link" onchange="toggleModalUploadTipe('link')">
                                <label class="form-check-label fw-semibold small text-dark" for="modalTipeLink">
                                    <i class="fab fa-google-drive text-success me-1"></i> Link Google Drive
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3" id="wrapperNamaDokumen">
                        <label class="form-label fw-semibold text-dark">Nama Dokumen</label>
                        <input type="text" class="form-control" name="nama_dokumen" id="inputModalNamaDokumen" placeholder="Contoh: Bahan Paparan Supervisi">
                    </div>

                    {{-- Form File --}}
                    <div class="mb-3" id="wrapperModalFile">
                        <label class="form-label fw-semibold text-dark">Pilih File <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="file" required id="inputModalFile">
                        <div class="form-text small" id="textModalFileHelp">Format didukung: PDF, DOC, DOCX, JPG, PNG (Maks 50MB)</div>
                    </div>

                    {{-- Form Link Google Drive --}}
                    <div class="mb-3" id="wrapperModalLink" style="display: none;">
                        <label class="form-label fw-semibold text-dark">URL Link Google Drive <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fab fa-google-drive text-success"></i></span>
                            <input type="url" class="form-control" name="link_drive" id="inputModalLinkDrive" placeholder="https://drive.google.com/file/d/... atau folder">
                        </div>
                        <div class="form-text small text-muted">Pastikan izin akses tautan Google Drive disetel "Siapa saja yang memiliki link" agar dapat dibuka.</div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4" id="btnModalUploadSubmit"><i class="fas fa-check me-1"></i> Simpan Dokumen</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Khusus Upload Dokumen BA Pengawasan (Tersinkron Otomatis ke Sistem BA Utama) --}}
<div class="modal fade" id="modalUploadBa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('timeline-tahapan.upload', $pelakuUsaha->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="tahap" value="pengawasan">
                <input type="hidden" name="kategori" value="ba_pengawasan">
                
                <div class="modal-header border-bottom bg-light">
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
                        <span class="rounded-circle bg-primary-subtle text-primary p-2 d-inline-flex align-items-center justify-content-center" style="width:34px; height:34px;">
                            <i class="fas fa-file-shield fs-6"></i>
                        </span>
                        Upload Berita Acara (BA) Pengawasan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 rounded-3 py-2 px-3 small mb-3 d-flex align-items-start gap-2">
                        <i class="fas fa-arrows-rotate text-primary mt-1"></i>
                        <div>
                            <strong>Sinkronisasi Dua Arah:</strong> Dokumen yang Anda unggah akan otomatis terdata dan menambah record Berita Acara di menu sistem pengawasan yang dipilih (misal: <em>BA WAS PRL</em>), sekaligus tampil di tahapan tindak lanjut ini.
                        </div>
                    </div>

                    {{-- Pilihan Tipe: File atau Link GDrive --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small">Pilihan Bentuk Berita Acara <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3 p-2 bg-white rounded-3 border">
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="radio" name="tipe" id="modalBaTipeFile" value="file" checked onchange="toggleModalBaTipe('file')">
                                <label class="form-check-label fw-semibold small text-dark" for="modalBaTipeFile">
                                    <i class="fas fa-file-arrow-up text-primary me-1"></i> Unggah File Dokumen
                                </label>
                            </div>
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="radio" name="tipe" id="modalBaTipeLink" value="link" onchange="toggleModalBaTipe('link')">
                                <label class="form-check-label fw-semibold small text-dark" for="modalBaTipeLink">
                                    <i class="fab fa-google-drive text-success me-1"></i> Link Google Drive
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small">Pilih Jenis Pengawasan / Berita Acara <span class="text-danger">*</span></label>
                        <select name="jenis_ba" id="selectJenisBa" class="form-select form-select-sm fw-semibold" required onchange="updateBaNomorPlaceholder(this.value)">
                            <option value="prl" selected>BA WAS PRL (Pemanfaatan Ruang Laut)</option>
                            <option value="alse">BA WAS ALSE (Alat Penangkapan Ikan)</option>
                            <option value="reklamasi">BA Reklamasi</option>
                            <option value="ppk">BA PPK (Pesisir & Pulau-Pulau Kecil)</option>
                            <option value="pencemaran">BA Pencemaran</option>
                            <option value="lainnya">Dokumen Pengawasan Lainnya / Pendukung</option>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-7">
                            <label class="form-label fw-semibold text-dark small">Nomor Berita Acara <span class="badge bg-light text-muted border">Opsional</span></label>
                            <input type="text" class="form-control form-control-sm" name="nomor_ba" id="inputNomorBa" placeholder="Contoh: BA-PRL-{{ date('Ymd') }}-XXXXX">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold text-dark small">Tanggal Pengawasan</label>
                            <input type="date" class="form-control form-control-sm" name="tanggal_pengawasan" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Keterangan / Nama Dokumen <span class="badge bg-light text-muted border">Opsional</span></label>
                        <input type="text" class="form-control form-control-sm" name="nama_dokumen" placeholder="Contoh: Scan BA Lapangan Hasil Pengawasan">
                    </div>

                    {{-- Form File BA --}}
                    <div class="mb-2" id="wrapperModalBaFile">
                        <label class="form-label fw-bold text-dark small">Pilih File Dokumen BA <span class="text-danger">*</span></label>
                        <input type="file" class="form-control form-control-sm" name="file" id="inputModalBaFile" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        <div class="form-text small" style="font-size: 0.72rem;">Format: PDF, DOCX, JPG, PNG (Maksimal 50MB)</div>
                    </div>

                    {{-- Form Link Google Drive BA --}}
                    <div class="mb-2" id="wrapperModalBaLink" style="display: none;">
                        <label class="form-label fw-bold text-dark small">Tautan Google Drive Dokumen BA <span class="text-danger">*</span></label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white"><i class="fab fa-google-drive text-success"></i></span>
                            <input type="url" name="link_drive" id="inputModalBaLinkDrive" class="form-control" placeholder="https://drive.google.com/file/d/... atau folder">
                        </div>
                        <div class="form-text small text-muted" style="font-size: 0.72rem;">Pastikan izin akses tautan Google Drive disetel "Siapa saja yang memiliki link" agar dapat dibuka.</div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-bold px-4">
                        <i class="fas fa-upload me-1"></i> Unggah & Sinkronkan ke Sistem
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Bulk Upload Peta Spasial --}}
<div class="modal fade" id="modalBulkUpload" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('timeline-tahapan.upload', $pelakuUsaha->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="tahap" value="supervisi">
                <input type="hidden" name="kategori" value="peta_spasial">
                
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold text-dark">Upload Banyak Foto Spasial Sekaligus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">
                        Pilih hingga 8 file foto sekaligus. Sistem akan otomatis mengisi slot foto yang masih kosong.
                    </p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Pilih Foto (Dapat memilih lebih dari 1)</label>
                        <input type="file" class="form-control" name="files[]" multiple accept="image/*" required>
                        <div class="form-text small">Format gambar: JPG, JPEG, PNG (Maks 8 foto, masing-masing maks 10MB)</div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">Unggah Foto-foto</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Preview Foto Spasial (Lightbox) --}}
<div class="modal fade" id="modalPreviewImage" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow bg-transparent">
            <div class="modal-header border-0 pb-0 justify-content-end">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-2">
                <img src="" id="imgPreviewTag" class="img-fluid rounded shadow-lg max-vh-80" alt="Preview">
                <div class="text-white mt-2 fw-semibold" id="previewCaption"></div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Detail NIB & Profil Tersinkronisasi --}}
<div class="modal fade" id="modalDetailNib" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom bg-light">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-id-card text-success me-2"></i>Data NIB & Profil (Tersinkronisasi OSS / Master Data)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped small mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 30%;">Nama Perusahaan</th>
                                <td>{{ $pelakuUsaha->nama_perusahaan }}</td>
                            </tr>
                            <tr>
                                <th>Nomor NIB</th>
                                <td><span class="badge bg-success">{{ $company?->nib ?? ($pelakuUsaha->nib ?? 'TER-SYNC VIA OSS') }}</span></td>
                            </tr>
                            <tr>
                                <th>Penanggung Jawab / PIC</th>
                                <td>{{ $pelakuUsaha->nama_pic ?? '-' }} {{ $pelakuUsaha->jabatan_pic ? '(' . $pelakuUsaha->jabatan_pic . ')' : '' }}</td>
                            </tr>
                            <tr>
                                <th>Kontak PIC (HP / Email)</th>
                                <td>{{ $pelakuUsaha->nomor_hp ?? '-' }} &bull; {{ $pelakuUsaha->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Alamat Lengkap</th>
                                <td>{{ $pelakuUsaha->alamat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Wilayah</th>
                                <td>{{ $pelakuUsaha->kelurahan?->nama ?? '-' }}, {{ $pelakuUsaha->kecamatan?->nama ?? '-' }}, {{ $pelakuUsaha->kabupaten?->nama ?? '-' }}, {{ $pelakuUsaha->provinsi?->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Koordinat Lokasi</th>
                                <td>Lat: {{ $pelakuUsaha->latitude ?? '-' }}, Long: {{ $pelakuUsaha->longitude ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Sumber Data</th>
                                <td><span class="badge bg-primary">Master Data Pelaku Usaha / Database Perusahaan OSS</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top">
                @can('kelola-master-data')
                    <a href="{{ route('pelaku-usaha.edit', $pelakuUsaha->id) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit me-1"></i> Edit Data Perusahaan</a>
                @endcan
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Detail Dokumen PKKPRL Tersinkronisasi --}}
<div class="modal fade" id="modalDetailPkkprl" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom bg-light">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-file-signature text-success me-2"></i>Dokumen PKKPRL (Tersinkronisasi Sistem KKP)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped small mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 30%;">Nomor PKKPRL</th>
                                <td><span class="badge bg-primary fs-6">{{ $pelakuUsaha->nomor_pkkprl ?? '-' }}</span></td>
                            </tr>
                            <tr>
                                <th>Jenis Usaha</th>
                                <td>{{ $pelakuUsaha->jenisUsaha?->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Luas PKKPRL</th>
                                <td>{{ $pelakuUsaha->luas_pkkprl ? number_format($pelakuUsaha->luas_pkkprl, 2) . ' m²' : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Status Verifikasi</th>
                                <td><span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Aktif / Terdaftar di Sistem KKP</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Detail Persyaratan Teknis Tersinkronisasi --}}
<div class="modal fade" id="modalDetailTeknis" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom bg-light">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-folder-open text-success me-2"></i>Persyaratan Teknis Pelaku Usaha (Tersinkronisasi)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                @php
                    $allDocs = $pelakuUsaha->dokumenPelakuUsahas->concat($pelakuUsaha->dokumens);
                @endphp
                @if($allDocs->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-file-circle-exclamation fs-2 mb-2 d-block text-warning"></i>
                        <p class="small mb-0">Belum ada file persyaratan teknis yang diunggah oleh pelaku usaha ini.</p>
                    </div>
                @else
                    <div class="table-responsive border rounded-3">
                        <table class="table table-hover table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Nama Dokumen</th>
                                    <th>Jenis</th>
                                    <th>Tanggal Upload</th>
                                    <th class="text-center" style="width: 100px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pelakuUsaha->dokumenPelakuUsahas as $dpu)
                                <tr>
                                    <td class="ps-3 fw-semibold">{{ $dpu->nama_file }}</td>
                                    <td><span class="badge bg-info text-dark">{{ $dpu->jenis_dokumen ?? 'Persyaratan Teknis' }}</span></td>
                                    <td class="small text-muted">{{ $dpu->tanggal_upload ? $dpu->tanggal_upload->format('d M Y') : $dpu->created_at->format('d M Y') }}</td>
                                    <td class="text-center">
                                        <a href="{{ asset('storage/' . $dpu->path_file) }}" target="_blank" class="btn btn-sm btn-light text-primary" title="Unduh">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                                @foreach($pelakuUsaha->dokumens as $pud)
                                <tr>
                                    <td class="ps-3 fw-semibold">{{ $pud->nama_file }}</td>
                                    <td><span class="badge bg-secondary">{{ $pud->jenis_dokumen }}</span></td>
                                    <td class="small text-muted">{{ $pud->tanggal_upload ? \Carbon\Carbon::parse($pud->tanggal_upload)->format('d M Y') : $pud->created_at->format('d M Y') }}</td>
                                    <td class="text-center">
                                        <a href="{{ asset('storage/' . $pud->path_file) }}" target="_blank" class="btn btn-sm btn-light text-primary" title="Unduh">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- Form Tersembunyi untuk Delete File --}}
<form id="formDeleteFile" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
    // Filter Riwayat Kejadian
    $('#tl-filter').on('click', 'button', function () {
        $('#tl-filter button').removeClass('active');
        $(this).addClass('active');
        const stage = $(this).data('stage');
        const wrap = $('#tl-events-wrap');
        wrap.find('.tl-event').each(function () {
            const show = !stage || $(this).data('stage') === stage;
            $(this).toggle(show);
        });
        wrap.find('.tl-year').each(function () {
            const li = $(this).closest('li');
            let visible = false;
            li.nextUntil(':has(.tl-year)').each(function () { if ($(this).is(':visible')) visible = true; });
            li.toggle(visible);
        });
    });

    // Helper Modal Upload
    function openUploadModal(tahap, kategori, defaultName, slot = '') {
        $('#modalUploadTahap').val(tahap);
        $('#modalUploadKategori').val(kategori);
        $('#modalUploadSlot').val(slot);
        $('#inputModalFile').val('');
        $('#inputModalLinkDrive').val('');
        $('#modalTipeFile').prop('checked', true);
        toggleModalUploadTipe('file');
        
        let title = 'Upload Dokumen';
        if (kategori === 'bahan_paparan') {
            title = 'Upload Bahan Paparan';
            $('#wrapperNamaDokumen').hide();
            $('#inputModalNamaDokumen').val('Bahan Paparan');
        } else if (kategori === 'permohonan_supervisi') {
            title = 'Upload Permohonan Supervisi';
            $('#wrapperNamaDokumen').hide();
            $('#inputModalNamaDokumen').val('Permohonan Supervisi');
        } else if (kategori === 'peta_spasial') {
            title = 'Upload ' + defaultName;
            $('#wrapperNamaDokumen').hide();
            $('#inputModalNamaDokumen').val(defaultName);
        } else {
            title = defaultName ? 'Upload ' + defaultName : 'Tambah Dokumen';
            $('#wrapperNamaDokumen').show();
            $('#inputModalNamaDokumen').val(defaultName);
        }
        
        $('#modalUploadTitle').text(title);
        $('#modalUpload').modal('show');
    }

    // Toggle Tipe di Modal Upload
    function toggleModalUploadTipe(type) {
        if (type === 'link') {
            $('#wrapperModalFile').hide();
            $('#inputModalFile').prop('required', false).val('');
            $('#wrapperModalLink').show();
            $('#inputModalLinkDrive').prop('required', true);
            $('#btnModalUploadSubmit').html('<i class="fab fa-google-drive me-1"></i> Simpan Link Drive');
        } else {
            $('#wrapperModalLink').hide();
            $('#inputModalLinkDrive').prop('required', false).val('');
            $('#wrapperModalFile').show();
            $('#inputModalFile').prop('required', true);
            $('#btnModalUploadSubmit').html('<i class="fas fa-upload me-1"></i> Unggah File');
        }
    }

    // Toggle Tipe di Modal Upload BA
    function toggleModalBaTipe(type) {
        if (type === 'link') {
            $('#wrapperModalBaFile').hide();
            $('#inputModalBaFile').prop('required', false).val('');
            $('#wrapperModalBaLink').show();
            $('#inputModalBaLinkDrive').prop('required', true);
        } else {
            $('#wrapperModalBaLink').hide();
            $('#inputModalBaLinkDrive').prop('required', false).val('');
            $('#wrapperModalBaFile').show();
            $('#inputModalBaFile').prop('required', true);
        }
    }

    // Toggle Tipe di Surat Pemberitahuan
    function togglePemberitahuanTipe(type) {
        if (type === 'link') {
            $('#wrapperPemberitahuanFile').hide();
            $('#inputPemberitahuanFile').prop('required', false).val('');
            $('#wrapperPemberitahuanLink').show();
            $('#inputPemberitahuanLink').prop('required', true);
        } else {
            $('#wrapperPemberitahuanLink').hide();
            $('#inputPemberitahuanLink').prop('required', false).val('');
            $('#wrapperPemberitahuanFile').show();
            $('#inputPemberitahuanFile').prop('required', true);
        }
    }

    // Helper Modal Khusus Upload BA Pengawasan
    function openUploadBaModal() {
        $('#inputNomorBa').val('');
        $('#selectJenisBa').val('prl');
        $('#modalBaTipeFile').prop('checked', true);
        toggleModalBaTipe('file');
        updateBaNomorPlaceholder('prl');
        $('#modalUploadBa').modal('show');
    }

    function updateBaNomorPlaceholder(val) {
        var inp = document.getElementById('inputNomorBa');
        if (!inp) return;
        var today = new Date().toISOString().slice(0, 10).replace(/-/g, '');
        if (val === 'prl') inp.placeholder = 'Contoh: BA-PRL-' + today + '-XXXXX';
        else if (val === 'alse') inp.placeholder = 'Contoh: BA-ALSE-' + today + '-XXXXX';
        else if (val === 'reklamasi') inp.placeholder = 'Contoh: BA-REK-' + today + '-XXXXX';
        else if (val === 'ppk') inp.placeholder = 'Contoh: BA-PPK-' + today + '-XXXXX';
        else if (val === 'pencemaran') inp.placeholder = 'Contoh: BA-PCM-' + today + '-XXXXX';
        else inp.placeholder = 'Auto-generate jika dikosongkan';
    }

    // Helper Preview Foto Spasial (Lightbox)
    function previewImage(src, caption) {
        $('#imgPreviewTag').attr('src', src);
        $('#previewCaption').text(caption);
        $('#modalPreviewImage').modal('show');
    }

    // Helper Konfirmasi Hapus File
    function confirmDeleteFile(url, nama) {
        if (confirm('Apakah Anda yakin ingin menghapus ' + nama + '?')) {
            const form = $('#formDeleteFile');
            form.attr('action', url);
            form.submit();
        }
    }
</script>
@endpush
