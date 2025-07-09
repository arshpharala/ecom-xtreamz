<div class="d-flex gap-2">
  @if (empty($row->deleted_at))
    @if (!empty($editUrl))
      <a href="{{ $editUrl }}" class="btn btn-sm btn-secondary">Edit</a>
    @endif
    @if (!empty($deleteUrl))
      <button type="button" class="btn btn-sm btn-danger btn-delete" data-url="{{ $deleteUrl }}">Delete</button>
    @endif
  @elseif(!empty($restoreUrl))
    <button data-url="{{ $restoreUrl }}" class="btn btn-sm btn-success btn-delete">Restore</button>
  @endif

</div>
