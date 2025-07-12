<form action="{{ route('admin.cms.locales.store') }}" method="post" class="ajax-form" enctype="multipart/form-data"
  onsubmit="handleFormSubmission(this)">
  @csrf
  <div class="p-3 border-bottom flex-shrink-0" style="background:#f8f9fa;">
    <div class="d-flex flex-row justify-content-between align-items-center">
      <h4 id="aside-heading" class="mb-0">Create Locale</h4>
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
            <label>Code</label>
            <input type="text" name="code" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Direction</label>
            <select name="direction" class="form-control" required>
              @foreach ($directions as $direction)
                <option value="{{ $direction->value }}">{{ $direction->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label>Logo</label>
            <input type="file" name="logo" class="form-control" accept="image/*">
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Fixed Buttons -->
  <div class="p-3 border-top flex-shrink-0 bg-white">
    <div class="d-flex flex-row justify-content-between">
      <button type="button" class="btn btn-outline-secondary" data-widget="control-sidebar"
        data-slide="true">Cancel</button>
      <button type="submit" class="btn btn-secondary">Save</button>
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
