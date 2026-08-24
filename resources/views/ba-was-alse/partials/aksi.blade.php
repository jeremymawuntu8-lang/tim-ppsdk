<div class="d-flex gap-1">
    <a href="{{ route('ba-was-alse.show', $row->id) }}" class="btn btn-sm btn-info" title="Detail"><i class="fas fa-eye"></i></a>
    <a href="{{ route('ba-was-alse.edit', $row->id) }}" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-pen"></i></a>
    <a href="{{ route('ba-was-alse.cetak', $row->id) }}" target="_blank" class="btn btn-sm btn-secondary" title="Cetak"><i class="fas fa-print"></i></a>
    <button type="button" onclick="confirmDelete('/ba-was-alse/{{ $row->id }}', () => table.draw())" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></button>
</div>
