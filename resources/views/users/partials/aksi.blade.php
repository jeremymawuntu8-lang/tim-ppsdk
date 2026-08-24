<div class="btn-group">
    <a href="{{ route('users.edit', $row->id) }}" class="btn btn-sm btn-warning text-white"><i class="fas fa-pen"></i></a>
    @if($row->id !== auth()->id())
    <button type="button" onclick="confirmDelete('/users/{{ $row->id }}', () => table.draw())" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
    @endif
</div>
