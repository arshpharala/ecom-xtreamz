<form action="{{ route('admin.catalog.products.store') }}" method="post" class="ajax-form" enctype="multipart/form-data"
  onsubmit="handleFormSubmission(this)">
  @csrf
  <div class="p-3 border-bottom flex-shrink-0" style="background:#f8f9fa;">
    <div class="d-flex flex-row justify-content-between align-items-center">
      <h4 id="aside-heading" class="mb-0">Product Brand</h4>
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
            <label for="category_id">Category</label>
            <select name="category_id" id="category_id" class="form-control" required>
              <option value="">Select Category</option>
              @foreach ($categories as $cat)
                <option value="{{ $cat->id }}">
                  {{ $cat->translations->where('locale', app()->getLocale())->first()?->name ?? $cat->slug }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label for="brand_id">Brand</label>
            <select name="brand_id" class="form-control">
              <option value="">None</option>
              @foreach ($brands as $brand)
                <option value="{{ $brand->id }}">
                  {{ $brand->name }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label>Slug</label>
            <input type="text" name="slug" class="form-control" required>
          </div>

          {{-- Name and Description fields for ALL LANGUAGES --}}
          @foreach (active_locals() as $locale)
            <div class="form-group">
              <label for="name_{{ $locale }}">Name ({{ strtoupper($locale) }})</label>
              <input type="text" name="name[{{ $locale }}]" class="form-control" required>
            </div>

            <div class="form-group">
              <label for="description_{{ $locale }}">Description ({{ strtoupper($locale) }})</label>
              <textarea name="description[{{ $locale }}]" class="form-control" rows="3"></textarea>
            </div>
          @endforeach

          <div class="form-group">
            <label for="position">Position</label>
            <input type="number" name="position" class="form-control">
          </div>

          <div class="form-group">
            <div class="custom-control custom-switch mb-2">
              <input type="checkbox" name="is_active" value="1" class="custom-control-input" id="is_active">
              <label class="custom-control-label" for="is_active">Active</label>
            </div>
            <div class="custom-control custom-switch mb-2">
              <input type="checkbox" name="is_featured" value="1" class="custom-control-input" id="is_featured">
              <label class="custom-control-label" for="is_featured">Featured</label>
            </div>
            <div class="custom-control custom-switch mb-2">
              <input type="checkbox" name="is_new" value="1" class="custom-control-input" id="is_new">
              <label class="custom-control-label" for="is_new">New Arrival</label>
            </div>
            <div class="custom-control custom-switch mb-2">
              <input type="checkbox" name="show_in_slider" value="1" class="custom-control-input"
                id="show_in_slider">
              <label class="custom-control-label" for="show_in_slider">Show in Slider</label>
            </div>
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
