<div class="btn-group">
    <a href="{{ route('ba-reklamasi.show', $row->id) }}" class="btn btn-sm btn-primary" title="Lihat"><i class="fas fa-eye"></i></a>
    <a href="{{ route('ba-reklamasi.edit', $row->id) }}" class="btn btn-sm btn-info" title="Edit"><i class="fas fa-edit"></i></a>
    <a href="{{ route('ba-reklamasi.cetak', $row->id) }}" target="_blank" class="btn btn-sm btn-secondary" title="Cetak PDF"><i class="fas fa-print"></i></a>
    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('{{ route('ba-reklamasi.destroy', $row->id) }}', () => table.draw(false))" title="Hapus"><i class="fas fa-trash"></i></button>
</div>
