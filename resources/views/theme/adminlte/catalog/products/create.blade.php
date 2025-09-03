<form action="{{ route('admin.catalog.products.store') }}" method="post" class="ajax-form" enctype="multipart/form-data"
  onsubmit="handleFormSubmission(this)">
  @csrf
  @include('theme.adminlte.components._aside-header', [
      'moduleName' => __('crud.create_title', ['name' => 'Product']),
  ])

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
  @include('theme.adminlte.components._aside-footer')

</form>
<script>
  $(document).ready(function() {
    $("form.ajax-form").each(function() {
      handleFormSubmission(this);
    });
  });

  $(function() {
    let $slug = $("input[name='slug']");
    let $firstName = $("input[name^='name']").first();

    $firstName.on("input", function() {
      if (!$slug.val().trim()) {
        let slug = $(this).val()
          .toLowerCase()
          .replace(/\s+/g, "-") // spaces → dash
          .replace(/[^a-z0-9\-]/g, "") // remove invalid chars
          .replace(/-+/g, "-") // collapse multiple dashes
          .replace(/^-+|-+$/g, ""); // trim leading/trailing dashes

        $slug.val(slug);
      }
    });
  });
</script>
