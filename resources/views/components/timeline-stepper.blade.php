@props(['stages', 'size' => 'lg'])

@php
    $stateText = [
        'done' => 'Selesai',
        'active' => 'Sedang berjalan',
        'alert' => 'Perlu tindakan',
        'next' => 'Tahap berikutnya',
        'skipped' => 'Dilewati',
        'pending' => 'Belum',
    ];
@endphp

<div class="tl-stepper-wrap">
    <ol class="tl-stepper {{ $size === 'sm' ? 'tl-sm' : '' }}">
        @foreach($stages as $key => $stage)
            @php
                $icon = match ($stage['state']) {
                    'done' => 'fa-check',
                    'skipped' => 'fa-minus',
                    'alert' => 'fa-exclamation',
                    default => $stage['icon'],
                };
                $tooltip = $stage['label'] . ' — ' . ($stateText[$stage['state']] ?? '') . ($stage['note'] ? ' (' . $stage['note'] . ')' : '');
            @endphp
            <li class="tl-step is-{{ $stage['state'] }}" title="{{ $tooltip }}" @if($size === 'sm') data-bs-toggle="tooltip" @endif>
                <span class="tl-step-dot"><i class="fas {{ $icon }}"></i></span>
                @if($size !== 'sm')
                    <div class="tl-step-label">{{ $stage['label'] }}</div>
                    <div class="tl-step-note">{{ $stage['note'] ?? ($stateText[$stage['state']] ?? '') }}</div>
                    @if(!empty($stage['date']))
                        <div class="tl-step-date"><i class="far fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($stage['date'])->translatedFormat('d M Y') }}</div>
                    @endif
                @endif
            </li>
        @endforeach
    </ol>
</div>
