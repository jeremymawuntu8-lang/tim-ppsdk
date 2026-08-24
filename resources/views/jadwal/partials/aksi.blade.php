<div class="btn-group">
    <a href="{{ route('jadwal.edit', $row->id) }}" class="btn btn-sm btn-warning text-white"><i class="fas fa-pen"></i></a>
    <button type="button" onclick="confirmDelete('/jadwal/{{ $row->id }}', () => table.draw())" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
</div>
