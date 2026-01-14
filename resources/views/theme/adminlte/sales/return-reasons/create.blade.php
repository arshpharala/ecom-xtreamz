<form action="{{ route('admin.sales.return-reasons.store') }}" method="post" class="ajax-form"
  onsubmit="handleFormSubmission(this)">
  @csrf
  @include('theme.adminlte.components._aside-header', [
      'moduleName' => 'Create Return Reason',
  ])

  <div class="flex-fill" style="overflow-y:auto; min-height:0; max-height:calc(100vh - 132px);">
    <div class="p-3" id="aside-inner-content">
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label>Reason</label>
            <input type="text" name="reason" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Status</label>
            <select name="is_active" class="form-control">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
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
