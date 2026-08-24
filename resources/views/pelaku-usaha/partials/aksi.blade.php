<div class="d-flex gap-1">
    {{-- Tombol Lihat: Dropdown ke masing-masing BA --}}
    @php
        $baLinks = [];
        foreach ($row->baWasPrls ?? [] as $ba) {
            $baLinks[] = ['label' => 'BA WAS PRL', 'url' => route('ba-was-prl.show', $ba->id), 'color' => 'primary', 'nomor' => $ba->nomor_ba];
        }
        foreach ($row->baWasAlses ?? [] as $ba) {
            $baLinks[] = ['label' => 'BA WAS ALSE', 'url' => route('ba-was-alse.show', $ba->id), 'color' => 'info', 'nomor' => $ba->nomor_ba];
        }
        foreach ($row->baReklamasis ?? [] as $ba) {
            $baLinks[] = ['label' => 'BA REKLAMASI', 'url' => route('ba-reklamasi.show', $ba->id), 'color' => 'success', 'nomor' => $ba->nomor_ba];
        }
        foreach ($row->baPpks ?? [] as $ba) {
            $baLinks[] = ['label' => 'BA PPK', 'url' => route('ba-ppk.show', $ba->id), 'color' => 'warning', 'nomor' => $ba->nomor_ba];
        }
        foreach ($row->baPencemarans ?? [] as $ba) {
            $baLinks[] = ['label' => 'BA PENCEMARAN', 'url' => route('ba-pencemaran.show', $ba->id), 'color' => 'danger', 'nomor' => $ba->nomor_ba];
        }
    @endphp

    @if(count($baLinks) === 1)
        <a href="{{ $baLinks[0]['url'] }}" class="btn btn-sm btn-info" title="Lihat {{ $baLinks[0]['label'] }}"><i class="fas fa-eye"></i></a>
    @elseif(count($baLinks) > 1)
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Lihat BA">
                <i class="fas fa-eye"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                @foreach($baLinks as $link)
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ $link['url'] }}">
                            <span class="badge bg-{{ $link['color'] }}" style="font-size: 0.65rem;">{{ $link['label'] }}</span>
                            <small class="text-muted text-truncate" style="max-width: 120px;">{{ $link['nomor'] }}</small>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <a href="{{ route('pelaku-usaha.show', $row->id) }}" class="btn btn-sm btn-info" title="Detail"><i class="fas fa-eye"></i></a>
    @endif

    <a href="{{ route('pelaku-usaha.edit', $row->id) }}" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-pen"></i></a>
    <button type="button" onclick="hapusPelakuUsaha({{ $row->id }})" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></button>
</div>
