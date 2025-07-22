<form action="{{ route('admin.cms.locales.update', $locale) }}" method="post" class="ajax-form"
  enctype="multipart/form-data" onsubmit="handleFormSubmission(this)">
  @csrf
  @method('PUT')
  <div class="p-3 border-bottom flex-shrink-0" style="background:#f8f9fa;">
    <div class="d-flex flex-row justify-content-between align-items-center">
      <h4 id="aside-heading" class="mb-0">Edit Locale</h4>
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
            <input type="text" name="code" value="{{ $locale->code }}" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="{{ $locale->name }}" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Direction</label>
            <select name="direction" class="form-control" required>
              @foreach ($directions as $direction)
                <option value="{{ $direction->value }}" @selected($direction->value == $locale->direction)>{{ $direction->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label>Logo</label>
            <input type="file" name="logo" class="form-control" accept="image/*">
            @if (isset($locale) && $locale->logo)
              <div class="mt-2">
                <img src="{{ asset('storage/' . $locale->logo) }}" class="img-lg img-thumbnail">
              </div>
            @endif
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
