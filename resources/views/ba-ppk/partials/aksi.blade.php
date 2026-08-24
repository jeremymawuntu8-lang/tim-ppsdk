<div class="btn-group btn-group-sm">
    <a href="{{ route('ba-ppk.show', $row->id) }}" class="btn btn-primary" title="Lihat">
        <i class="fas fa-eye"></i>
    </a>
    <a href="{{ route('ba-ppk.cetak', $row->id) }}" target="_blank" class="btn btn-secondary" title="Cetak PDF">
        <i class="fas fa-print"></i>
    </a>
    <a href="{{ route('ba-ppk.edit', $row->id) }}" class="btn btn-info text-white" title="Edit">
        <i class="fas fa-edit"></i>
    </a>
    <button type="button" class="btn btn-danger btn-hapus" data-id="{{ $row->id }}" data-url="{{ route('ba-ppk.destroy', $row->id) }}" title="Hapus">
        <i class="fas fa-trash"></i>
    </button>
</div>
