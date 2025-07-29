<form action="{{ route('admin.auth.admins.update', $admin->id) }}" method="post" class="ajax-form" enctype="multipart/form-data"
  onsubmit="handleFormSubmission(this)">
  @csrf
  @method('PUT')

  <div class="p-3 border-bottom flex-shrink-0" style="background:#f8f9fa;">
    <div class="d-flex flex-row justify-content-between align-items-center">
      <h4 id="aside-heading" class="mb-0">Edit User</h4>
      <a data-widget="control-sidebar" data-slide="true" href="#" role="button">
        <i class="fa fa-times"></i>
      </a>
    </div>
  </div>

  <!-- Scrollable Content -->
  <div class="flex-fill" style="overflow-y:auto; min-height:0; max-height:calc(100vh - 132px);">
    <div class="p-3" id="aside-inner-content">

      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="{{ $admin->name }}" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ $admin->email }}" class="form-control" required autocomplete="off">
          </div>

          <div class="form-group">
            <label>Status</label>
            <select name="is_active" class="form-control">
              <option value="1" {{ $admin->is_active ? 'selected' : '' }}>Active</option>
              <option value="0" {{ !$admin->is_active ? 'selected' : '' }}>Inactive</option>
            </select>
          </div>

          <div class="form-group">
            <label>New Password <small class="text-muted">(leave blank to keep unchanged)</small></label>
            <input type="password" name="password" class="form-control" autocomplete="new-password">
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Fixed Buttons -->
  <div class="p-3 border-top flex-shrink-0 bg-white">
    <div class="d-flex flex-row justify-content-between">
      <button type="button" class="btn btn-outline-secondary" data-widget="control-sidebar" data-slide="true">Cancel</button>
      <button type="submit" class="btn btn-secondary">Update</button>
    </div>
  </div>
</form>

<script>
  $(document).ready(function() {
    $("form.ajax-form").each(function() {
      handleFormSubmission(this);
    });
  });
</script>
