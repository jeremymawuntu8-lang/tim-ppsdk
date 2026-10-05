@extends('layouts.app')
@section('title', 'Timeline Pengawasan')
@section('page-title', 'Timeline Pengawasan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Timeline Pengawasan</li>
@endsection

@php
    $cards = [
        'belum'    => ['label' => 'Belum Ada Pengawasan', 'icon' => 'fa-hourglass-start', 'color' => 'secondary'],
        'jadwal'   => ['label' => 'Tahap Jadwal',         'icon' => $stages['jadwal']['icon'],   'color' => 'primary'],
        'telaah'   => ['label' => 'Telaah Dokumen',       'icon' => $stages['telaah']['icon'],   'color' => 'purple'],
        'lapangan' => ['label' => 'Pengawasan Lapangan',  'icon' => $stages['lapangan']['icon'], 'color' => 'info'],
        'sp'       => ['label' => 'Surat Peringatan',     'icon' => $stages['sp']['icon'],       'color' => 'orange'],
        'selesai'  => ['label' => 'Selesai',              'icon' => $stages['selesai']['icon'],  'color' => 'success'],
    ];
    $currentColor = ['belum' => 'secondary', 'jadwal' => 'primary', 'telaah' => 'purple', 'lapangan' => 'info', 'sp' => 'orange', 'selesai' => 'success'];
    $tahap = request('tahap');
@endphp

@section('content')
<div class="fade-in">
    {{-- Ringkasan per tahap --}}
    <div class="card card-primary card-outline shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="card-title fw-bold mb-0 text-dark"><i class="fas fa-timeline me-2 text-primary"></i>Progres Pengawasan Seluruh Pelaku Usaha</h5>
                <small class="text-muted">{{ number_format($total) }} pelaku usaha · klik kartu untuk memfilter berdasarkan tahap saat ini</small>
            </div>
            @if($summary['perlu_tindakan'] > 0)
                <a href="{{ request()->fullUrlWithQuery(['tahap' => 'perlu_tindakan', 'page' => null]) }}"
                   class="btn btn-sm {{ $tahap === 'perlu_tindakan' ? 'btn-danger' : 'btn-outline-danger' }} fw-semibold">
                    <i class="fas fa-bell me-1"></i> {{ $summary['perlu_tindakan'] }} Perlu Tindak Lanjut
                </a>
            @endif
        </div>
        <div class="card-body">
            <div class="tl-summary">
                @foreach($cards as $key => $c)
                    <a href="{{ request()->fullUrlWithQuery(['tahap' => $tahap === $key ? null : $key, 'page' => null]) }}"
                       class="tl-summary-card tl-c-{{ $c['color'] }} {{ $tahap === $key ? 'active' : '' }}">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="tl-summary-icon"><i class="fas {{ $c['icon'] }}"></i></span>
                            <span class="tl-summary-count">{{ $summary[$key] ?? 0 }}</span>
                        </div>
                        <div class="tl-summary-label">{{ $c['label'] }}</div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Daftar --}}
    <div class="card card-primary card-outline shadow-sm">
        <div class="card-body border-bottom bg-light py-3">
            <form method="GET" action="{{ route('timeline-pengawasan.index') }}" class="row g-2 align-items-center">
                @if($tahap)<input type="hidden" name="tahap" value="{{ $tahap }}">@endif
                <div class="col-md-4 col-12">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="q" id="timeline-search" class="form-control" placeholder="Cari nama perusahaan / nomor PKKPRL..." value="{{ request('q') }}">
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <select name="kabupaten_id" id="timeline-kabupaten" class="form-select form-select-sm">
                        <option value="">-- Semua Kabupaten/Kota --</option>
                        @foreach($kabupatens as $kab)
                            <option value="{{ $kab->id }}" @selected(request('kabupaten_id') == $kab->id)>{{ $kab->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-6">
                    <select name="urut" id="timeline-urut" class="form-select form-select-sm">
                        <option value="">Urut: Nama</option>
                        <option value="aktivitas" @selected(request('urut') === 'aktivitas')>Urut: Aktivitas terbaru</option>
                        <option value="progress" @selected(request('urut') === 'progress')>Urut: Progres tertinggi</option>
                    </select>
                </div>
                <div class="col-md-3 col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary flex-fill"><i class="fas fa-filter me-1"></i> Terapkan</button>
                    @if(request()->anyFilled(['q', 'kabupaten_id', 'urut', 'tahap']))
                        <a href="{{ route('timeline-pengawasan.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-times"></i></a>
                    @endif
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            @if($items->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-folder-open fa-2x mb-2 opacity-50"></i>
                    <h6 class="fw-bold text-dark mb-1">Tidak ada data</h6>
                    <p class="small mb-0">Tidak ada pelaku usaha yang sesuai filter.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Pelaku Usaha</th>
                                <th style="min-width: 220px;">Progres Tahapan</th>
                                <th style="min-width: 180px;">Tahap Saat Ini</th>
                                <th style="width: 150px;">Aktivitas Terakhir</th>
                                <th class="text-center" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $pu)
                                @php $t = $pu->timeline; @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $pu->nama_perusahaan }}</div>
                                        <div class="text-muted small">
                                            <i class="fas fa-location-dot me-1"></i>{{ $pu->kabupaten->nama ?? '-' }}
                                            @if($pu->jenisUsaha) · {{ $pu->jenisUsaha->nama }} @endif
                                        </div>
                                    </td>
                                    <td><x-timeline-stepper :stages="$t['stages']" size="sm" /></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="tl-stage-tag tl-c-{{ $currentColor[$t['current']] ?? 'secondary' }}">{{ $t['current_label'] }}</span>
                                            @if($t['perlu_tindakan'])
                                                <span class="tl-badge tl-badge-sp" title="Perlu tindak lanjut / surat peringatan"><i class="fas fa-bell"></i></span>
                                            @endif
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="tl-progress flex-fill"><div style="width: {{ $t['progress'] }}%"></div></div>
                                            <small class="fw-bold text-muted">{{ $t['progress'] }}%</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($t['last_activity'])
                                            <div class="small fw-semibold text-dark">{{ $t['last_activity']->translatedFormat('d M Y') }}</div>
                                            <div class="text-muted" style="font-size: .72rem;">{{ $t['last_activity']->diffForHumans() }}</div>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('timeline-pengawasan.show', $pu->id) }}" class="btn btn-sm btn-outline-primary fw-semibold" id="btn-timeline-{{ $pu->id }}">
                                            <i class="fas fa-timeline me-1"></i> Lihat
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($items->hasPages())
                    <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <small class="text-muted">Menampilkan {{ $items->firstItem() }}–{{ $items->lastItem() }} dari {{ $items->total() }}</small>
                        {{ $items->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    {{-- Legenda --}}
    <div class="d-flex flex-wrap gap-3 mt-3 small text-muted">
        <span><i class="fas fa-circle text-success me-1"></i>Selesai</span>
        <span><i class="fas fa-circle me-1" style="color:#F57C00"></i>Sedang berjalan</span>
        <span><i class="fas fa-circle text-danger me-1"></i>Perlu tindakan</span>
        <span><i class="far fa-circle text-primary me-1"></i>Tahap berikutnya</span>
        <span><i class="far fa-circle me-1" style="color:#81C784"></i>Dilewati / tidak diperlukan</span>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
    $('#timeline-kabupaten, #timeline-urut').on('change', function () { this.form.submit(); });
</script>
@endpush
