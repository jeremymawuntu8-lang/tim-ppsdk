<div class="btn-group">
    <a href="{{ route('pengawasan-tidak-langsung.show', $row->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
        <i class="fas fa-eye"></i>
    </a>
    <a href="{{ route('pengawasan-tidak-langsung.edit', $row->id) }}" class="btn btn-sm btn-warning" title="Edit Data">
        <i class="fas fa-edit"></i>
    </a>
    <a href="{{ route('pengawasan-tidak-langsung.cetak', $row->id) }}" target="_blank" class="btn btn-sm btn-success" title="Cetak PDF">
        <i class="fas fa-print"></i>
    </a>
    <button type="button" class="btn btn-sm btn-danger" onclick="hapusData({{ $row->id }})" title="Hapus Data">
        <i class="fas fa-trash"></i>
    </button>
</div>
