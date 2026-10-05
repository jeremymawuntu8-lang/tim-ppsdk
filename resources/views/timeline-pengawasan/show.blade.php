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

            <x-timeline-stepper :stages="$timeline['stages']" />
        </div>
    </div>

    <div class="row g-4">
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

            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body small text-muted">
                    <div class="fw-bold text-dark mb-2"><i class="fas fa-circle-info me-1 text-info"></i>Cara tahap dihitung</div>
                    <ul class="ps-3 mb-0">
                        <li><b>Jadwal</b> otomatis selesai jika sudah ada telaah/BA/SP.</li>
                        <li><b>Telaah Dokumen</b> dari menu Pengawasan Tidak Langsung.</li>
                        <li><b>Pengawasan Lapangan</b> dari semua jenis BA; berjalan bila ada BA berstatus Draft/Proses.</li>
                        <li><b>Surat Peringatan</b> wajib bila ada BA/telaah berstatus <i>Tindak Lanjut</i> atau laporan KKPRL tidak disampaikan.</li>
                        <li><b>Selesai</b> bila semua BA &amp; telaah berstatus <i>Selesai</i>.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
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
</script>
@endpush
