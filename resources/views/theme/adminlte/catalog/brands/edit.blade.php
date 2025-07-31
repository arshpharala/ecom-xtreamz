<form action="{{ route('admin.catalog.brands.update', $brand->id) }}" method="post" class="ajax-form"
  enctype="multipart/form-data" onsubmit="handleFormSubmission(this)">
  @csrf
  @method('PUT')
  @include('theme.adminlte.components._aside-header', [
      'moduleName' => __('crud.edit_title', ['name' => 'Brand']),
  ])

  <!-- Scrollable Content -->
  <div class="flex-fill" style="overflow-y:auto; min-height:0; max-height:calc(100vh - 132px);">
    <div class="p-3" id="aside-inner-content">

      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $brand->name ?? '') }}"
              required>
          </div>
          <div class="form-group">
            <label>Slug</label>
            <input type="text" name="slug" class="form-control" value="{{ old('slug', $brand->slug ?? '') }}"
              required>
          </div>

          <div class="form-group">
            <label>Status</label>
            <select name="is_active" class="form-control">
              <option value="1" @if (old('is_active', $brand->is_active ?? 1)) selected @endif>Active</option>
              <option value="0" @if (isset($brand) && !$brand->is_active) selected @endif>Inactive</option>
            </select>
          </div>
          <div class="form-group">
            <label>Position</label>
            <input type="number" name="position" class="form-control"
              value="{{ old('position', $brand->position ?? 0) }}">
          </div>
          <div class="form-group">
            <label>Logo</label>

            <input type="file" name="logo" class="form-control" accept="image/*">

            @if (isset($brand) && $brand->logo)
              <div class="mt-2">
                <img src="{{ asset('storage/' . $brand->logo) }}" class="img-lg img-thumbnail">
              </div>
            @endif
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Fixed Buttons -->
  @include('theme.adminlte.components._aside-footer')

</form>
<script>
  $(document).ready(function() {
    $("form.ajax-form").each(function() {
      handleFormSubmission(this);
    });
  });
</script>
