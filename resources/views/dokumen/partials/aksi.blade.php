<div class="btn-group">
    <a href="{{ route('dokumen.download', $row->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i></a>
    <button type="button" onclick="confirmDelete('/dokumen/{{ $row->id }}', () => table.draw())" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
</div>
