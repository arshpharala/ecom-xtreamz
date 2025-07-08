<div class="card">
  <div class="card-header">
    <h5 class="card-title">Product Variants</h5>
  </div>

  <form action="{{ route('admin.catalog.products.variants.store', $product->id) }}" method="POST" class="ajax-form">
    @csrf
    <div class="card-body">
      <div id="variant-repeater">
        @foreach ($product->variants as $variant)
          <div class="variant-row row mb-3">
            @foreach ($attributes as $attribute)
              <div class="col-md-4">
                <select name="variants[{{ $loop->parent->index }}][attributes][{{ $attribute->id }}]"
                  class="form-control" required>
                  <option value="">Select {{ $attribute->name }}</option>
                  @foreach ($attribute->values as $value)
                    <option value="{{ $value->id }}" @if ($variant->attributeValues->contains('id', $value->id)) selected @endif>
                      {{ $value->value }}
                    </option>
                  @endforeach
                </select>
              </div>
            @endforeach
            <div class="col-md-4">
              <input type="number" name="variants[{{ $loop->index }}][price]" class="form-control" placeholder="Price"
                value="{{ $variant->price }}" required>
            </div>
            <div class="col-md-3">
              <input type="number" name="variants[{{ $loop->index }}][stock]" class="form-control" placeholder="Stock"
                value="{{ $variant->stock }}" required>
            </div>
            <div class="col-md-1">
              <button type="button" class="btn btn-danger remove-variant">×</button>
            </div>
          </div>
        @endforeach
      </div>

      <button type="button" class="btn btn-sm btn-info mt-2" id="add-variant">Add Variant</button>
    </div>

    <div class="card-footer">
      <button type="submit" class="btn btn-primary">Save Variants</button>
    </div>
  </form>
</div>

@push('scripts')
  <script>
    let variantIndex = {{ $product->variants->count() }};

    $('#add-variant').on('click', function() {
      let row = `<div class="variant-row row mb-3">`;

      @foreach ($attributes as $attribute)
        row += `
        <div class="col-md-4">
          <select name="variants[${variantIndex}][attributes][{{ $attribute->id }}]" class="form-control" required>
            <option value="">Select {{ $attribute->name }}</option>
            @foreach ($attribute->values as $value)
              <option value="{{ $value->id }}">{{ $value->value }}</option>
            @endforeach
          </select>
        </div>`;
      @endforeach

      row += `
      <div class="col-md-4">
        <input type="number" name="variants[${variantIndex}][price]" class="form-control" placeholder="Price" required>
      </div>
      <div class="col-md-3">
        <input type="number" name="variants[${variantIndex}][stock]" class="form-control" placeholder="Stock" required>
      </div>
      <div class="col-md-1">
        <button type="button" class="btn btn-danger remove-variant">×</button>
      </div>
    </div>`;

      $('#variant-repeater').append(row);
      variantIndex++;
    });

    $(document).on('click', '.remove-variant', function() {
      $(this).closest('.variant-row').remove();
    });
  </script>
@endpush
