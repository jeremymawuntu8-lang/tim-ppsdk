@extends('layouts.app')
@section('title', 'Timeline Pengawasan - ' . $pelakuUsaha->nama_perusahaan)
@section('page-title', 'Timeline Pengawasan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('timeline-pengawasan.index') }}">Timeline Pengawasan</a></li>
    <li class="breadcrumb-item active">{{ \Illuminate\Support\Str::limit($pelakuUsaha->nama_perusahaan, 40) }}</li>
@endsection

@php
    $events = $timeline['events'];
    $countBy = $events->groupBy('stage')->map->count();
    $currentColor = ['belum' => 'secondary', 'jadwal' => 'primary', 'telaah' => 'purple', 'lapangan' => 'info', 'sp' => 'orange', 'selesai' => 'success'];
@endphp

@section('content')
<div class="fade-in">
    {{-- Header --}}
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
                    <a href="{{ route('timeline-pengawasan.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
                </div>
            </div>

            <hr class="my-4">

            <x-timeline-stepper :stages="$timeline['stages']" :clickable="true" />
            
            @if(request('action') !== 'tindak-lanjut')
            <div class="text-center mt-4 pt-2">
                <a href="?action=tindak-lanjut&tab={{ $timeline['current'] === 'selesai' || $timeline['current'] === 'belum' ? 'pemberitahuan' : $timeline['current'] }}" class="btn btn-primary btn-lg fw-bold shadow-sm px-5 rounded-pill">
                    <i class="fas fa-pen-to-square me-2"></i> Mulai / Update Tindak Lanjut Tahapan
                </a>
            </div>
            @endif
        </div>
    </div>

    @if(request('action') === 'tindak-lanjut')
        @php
            $activeStageKey = request('tab', $timeline['current'] === 'selesai' || $timeline['current'] === 'belum' ? 'supervisi' : $timeline['current']);
            $activeTahapRecord = $pelakuUsaha->timelineTahapans->firstWhere('tahap', $activeStageKey);
            $files = $activeTahapRecord ? $activeTahapRecord->files : collect();
            $stageLabel = \App\Services\TimelinePengawasanService::STAGES[$activeStageKey]['label'] ?? 'Terkait';
        @endphp
        
        <div class="row mt-4">
            <div class="col-12">
                <form action="{{ route('timeline-tahapan.upload', $pelakuUsaha->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="tahap" value="{{ $activeStageKey }}">
                    
                    {{-- 1. Dokumen Utama --}}
                    <div class="card card-primary card-outline shadow-sm mb-4 border-top-3 border-primary">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title fw-bold mb-0 text-dark">Dokumen Utama {{ $stageLabel }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">Nama Dokumen</label>
                                    <input type="text" class="form-control" name="nama_dokumen" id="inputNamaDokumen" placeholder="Contoh: Bahan Paparan, dll">
                                </div>
                                <div class="col-md-6" id="wrapperUploadFile" style="display: none;">
                                    <label class="form-label fw-semibold text-dark">Upload File</label>
                                    <input type="file" class="form-control" name="file" id="inputFileUpload">
                                </div>
                            </div>

                            @if($files->isNotEmpty())
                            <div class="mt-4">
                                <h6 class="fw-bold mb-3 text-secondary">Dokumen Terunggah:</h6>
                                <div class="table-responsive border rounded">
                                    <table class="table table-hover table-sm mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-3">Nama Dokumen</th>
                                                <th>Tanggal</th>
                                                <th class="text-center" style="width:100px;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($files as $f)
                                            <tr>
                                                <td class="ps-3 align-middle"><i class="fas fa-file-pdf text-danger me-2"></i>{{ $f->nama_dokumen }}</td>
                                                <td class="align-middle">{{ $f->created_at->format('d M Y H:i') }}</td>
                                                <td class="text-center align-middle">
                                                    <a href="{{ asset('storage/' . $f->file_path) }}" target="_blank" class="btn btn-sm btn-light text-primary"><i class="fas fa-download"></i></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- 2. Dokumen Pelaku Usaha (Tersinkronisasi) --}}
                    <div class="card card-primary card-outline shadow-sm mb-4">
                        <div class="card-header bg-white py-3 border-bottom-0">
                            <h5 class="card-title fw-bold mb-0 text-dark">Dokumen Pelaku Usaha <span class="badge bg-success ms-2 fw-normal" style="font-size:0.75rem">Tersinkronisasi</span></h5>
                        </div>
                        <div class="card-body pt-0 pb-4 px-4">
                            <div class="row g-3 mt-1">
                                <div class="col-md-4">
                                    <div class="border border-secondary-subtle rounded p-3 bg-white h-100 d-flex align-items-center gap-3">
                                        <div class="bg-light rounded p-2 text-secondary"><i class="fas fa-file-contract fs-4"></i></div>
                                        <div>
                                            <div class="fw-bold text-dark">NIB & Profil</div>
                                            <div class="small text-muted">Tersedia</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border border-secondary-subtle rounded p-3 bg-white h-100 d-flex align-items-center gap-3">
                                        <div class="bg-light rounded p-2 text-secondary"><i class="fas fa-file-signature fs-4"></i></div>
                                        <div>
                                            <div class="fw-bold text-dark">Dokumen PKKPRL</div>
                                            <div class="small text-muted">Tersedia</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border border-secondary-subtle rounded p-3 bg-white h-100 d-flex align-items-center gap-3">
                                        <div class="bg-light rounded p-2 text-secondary"><i class="fas fa-file-invoice fs-4"></i></div>
                                        <div>
                                            <div class="fw-bold text-dark">Persyaratan Teknis</div>
                                            <div class="small text-muted">Tersedia</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Peta Spasial --}}
                    <div class="card card-primary card-outline shadow-sm mb-4">
                        <div class="card-header bg-white py-3 border-bottom-0">
                            <h5 class="card-title fw-bold mb-0 text-dark">Peta Spasial <span class="fw-normal text-muted" style="font-size:0.9rem">(Maks 8 Foto)</span></h5>
                        </div>
                        <div class="card-body pt-0 px-4 pb-4">
                            <div class="border border-dashed rounded p-4 text-center bg-light" style="border-width: 2px !important; border-style: dashed !important; border-color: #dee2e6 !important;">
                                <i class="fas fa-cloud-upload-alt text-primary fs-2 mb-2"></i>
                                <div class="fw-semibold text-dark">Klik untuk unggah atau seret foto ke sini</div>
                                <div class="small text-muted mt-1">Format didukung: JPG, PNG, JPEG</div>
                                <input type="file" class="d-none" name="peta_spasial[]" multiple accept="image/*">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <a href="?action=" class="btn btn-light border px-4">Batal</a>
                        <div class="d-flex gap-2">
                            <button type="submit" name="action_type" value="upload" class="btn btn-outline-primary px-4 fw-semibold">Upload Dokumen Saja</button>
                            <button type="submit" name="action_type" value="submit" class="btn btn-primary px-4 fw-bold shadow-sm">Submit & Tandai Selesai</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="row g-4 mt-2">
            {{-- Riwayat kronologis --}}
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

            {{-- Ringkasan tahap --}}
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
                                        <div class="fw-semibold text-dark small">{{ $st['label'] }}</div>
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
    @endif
</div>
@endsection

@push('scripts')
<script>
    $('#tl-filter').on('click', 'button', function () {
        $('#tl-filter button').removeClass('active');
        $(this).addClass('active');
        const stage = $(this).data('stage');
        const wrap = $('#tl-events-wrap');
        wrap.find('.tl-event').each(function () {
            const show = !stage || $(this).data('stage') === stage;
            $(this).toggle(show);
        });
        // Sembunyikan label tahun yang tidak memiliki event terlihat
        wrap.find('.tl-year').each(function () {
            const li = $(this).closest('li');
            let visible = false;
            li.nextUntil(':has(.tl-year)').each(function () { if ($(this).is(':visible')) visible = true; });
            li.toggle(visible);
        });
    });

    // Wajib isi Nama Dokumen sebelum bisa upload file
    $('#inputNamaDokumen').on('input', function() {
        if ($(this).val().trim() !== '') {
            $('#wrapperUploadFile').fadeIn();
        } else {
            $('#wrapperUploadFile').fadeOut();
            $('#inputFileUpload').val(''); // Reset file jika input nama dihapus
        }
    });
</script>
@endpush
