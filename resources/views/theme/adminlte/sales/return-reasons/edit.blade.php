<form action="{{ route('admin.sales.return-reasons.update', $reason->id) }}" method="post" class="ajax-form"
  onsubmit="handleFormSubmission(this)">
  @csrf
  @method('PUT')
  @include('theme.adminlte.components._aside-header', [
      'moduleName' => 'Edit Return Reason',
  ])

  <div class="flex-fill" style="overflow-y:auto; min-height:0; max-height:calc(100vh - 132px);">
    <div class="p-3" id="aside-inner-content">
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label>Reason</label>
            <input type="text" name="reason" class="form-control" value="{{ $reason->reason }}" required>
          </div>
          <div class="form-group">
            <label>Status</label>
            <select name="is_active" class="form-control">
              <option value="1" {{ $reason->is_active ? 'selected' : '' }}>Active</option>
              <option value="0" {{ !$reason->is_active ? 'selected' : '' }}>Inactive</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>

  @include('theme.adminlte.components._aside-footer')
</form>
<script>
  $(document).ready(function() {
    $("form.ajax-form").each(function() {
      handleFormSubmission(this);
    });
  });
</script>
