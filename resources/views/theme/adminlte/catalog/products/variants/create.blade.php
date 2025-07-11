<form action="{{ route('admin.catalog.product.variants.store', $product->id) }}" method="post" class="ajax-form"
  enctype="multipart/form-data" onsubmit="handleFormSubmission(this)">
  @csrf
  <div class="p-3 border-bottom flex-shrink-0" style="background:#f8f9fa;">
    <div class="d-flex flex-row justify-content-between align-items-center">
      <h4 id="aside-heading" class="mb-0">Create Variant</h4>
      <a data-widget="control-sidebar" data-slide="true" href="#" role="button">
        <i class="fa fa-times"></i>
      </a>
    </div>
  </div>

  <!-- Scrollable Content -->
  <div class="flex-fill" style="overflow-y:auto; min-height:0; max-height:calc(100vh - 132px);">
    <div class="p-3" id="aside-inner-content">

      <div class="row">

        <div class="col-12">
          <div class="form-group">
            <label for="">SKU</label>
            <input type="text" name="sku" class="form-control" value="" required>
          </div>
        </div>
        <div class="col-6">
          <div class="form-group">
            <label for="">Price</label>
            <input type="number" name="price" class="form-control" value="" required>
          </div>
        </div>
        <div class="col-6">
          <div class="form-group">
            <label for="">Stock</label>
            <input type="number" name="stock" class="form-control" value="" required>
          </div>
        </div>
        <div class="col-12">

          @foreach ($attributes as $attribute)
            <div class="form-group">
              <label for="">{{ $attribute['name'] }}</label>
              <select name="attributes[{{ $attribute['id'] }}]" class="form-control" required>
                <option value="">Select</option>
                @foreach ($attribute['values'] as $val)
                  <option value="{{ $val['id'] }}">
                    {{ $val['value'] }}
                  </option>
                @endforeach
              </select>
            </div>
          @endforeach
        </div>
        <div class="col-6">

            <div class="form-group">
              <label for="length">Length</label>
              <input type="number" name="length" class="form-control" step="0.2">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
              <label for="width">Width</label>
              <input type="number" name="width" class="form-control" step="0.2">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
              <label for="height">Height</label>
              <input type="number" name="height" class="form-control" step="0.2">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
              <label for="weight">Weight</label>
              <input type="number" name="weight" class="form-control" step="0.2">
            </div>
        </div>
        <div class="col-12">

          <!-- ...Other Fields... -->
          <div class="form-group">
            <label>Variant Images</label>
            <div class="upload__box">
              <div class="upload__btn-box">
                <label class="upload__btn btn btn-outline-primary">Upload images
                  <input type="file" name="attachments[]" multiple data-max_length="5" class="upload__inputfile"
                    accept="image/*" />
                </label>
              </div>
              <div class="upload__img-wrap"></div>
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
  ImgUpload();

  $(document).ready(function() {
    $("form.ajax-form").each(function() {
      handleFormSubmission(this);
    });
  });
</script>
