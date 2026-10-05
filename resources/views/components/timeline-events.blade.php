@props(['events', 'limit' => null])

@php
    $stageMeta = \App\Services\TimelinePengawasanService::STAGES;
    $stageColor = ['jadwal' => 'primary', 'telaah' => 'purple', 'lapangan' => 'info', 'sp' => 'danger', 'selesai' => 'success'];
    $list = $limit ? $events->take($limit) : $events;
    $lastYear = null;
@endphp

@if($list->isEmpty())
    <div class="text-center py-5 text-muted">
        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:70px;height:70px;">
            <i class="fas fa-timeline fa-2x opacity-50"></i>
        </div>
        <h6 class="fw-bold text-dark mb-1">Belum Ada Riwayat</h6>
        <p class="small mb-0">Belum ada jadwal, telaah, BA, maupun surat peringatan untuk pelaku usaha ini.</p>
    </div>
@else
    <ul class="tl-events">
        @foreach($list as $i => $e)
            @php $year = $e['date']->year; @endphp
            @if($year !== $lastYear)
                <li><span class="tl-year"><i class="far fa-calendar"></i>{{ $year }}</span></li>
                @php $lastYear = $year; @endphp
            @endif
            <li class="tl-event tl-c-{{ $e['color'] }}" data-stage="{{ $e['stage'] }}" style="animation-delay: {{ min($i, 12) * 0.04 }}s">
                <span class="tl-event-icon"><i class="fas {{ $e['icon'] }}"></i></span>
                @php $tag = $e['url'] ? 'a' : 'div'; @endphp
                <{{ $tag }} @if($e['url']) href="{{ $e['url'] }}" @endif class="tl-event-card">
                    <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap">
                        <div class="tl-event-title">{{ $e['title'] }}</div>
                        @if($e['badge'])
                            <span class="tl-badge tl-badge-{{ \Illuminate\Support\Str::slug($e['badge_status'] ?? 'default') }}">{{ $e['badge'] }}</span>
                        @endif
                    </div>
                    <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                        <span class="tl-event-meta">
                            <i class="far fa-clock me-1"></i>{{ $e['date']->translatedFormat('d F Y') }}
                            @if($e['date_estimated'])
                                <span class="text-warning" title="Tanggal tidak tercatat, menggunakan tanggal input data">(perkiraan)</span>
                            @endif
                        </span>
                        <span class="tl-stage-tag tl-c-{{ $stageColor[$e['stage']] ?? 'secondary' }}">{{ $stageMeta[$e['stage']]['label'] ?? $e['stage'] }}</span>
                    </div>
                    @if($e['desc'])
                        <div class="tl-event-desc">{{ $e['desc'] }}</div>
                    @endif
                </{{ $tag }}>
            </li>
        @endforeach
    </ul>
@endif
