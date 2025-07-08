@extends('theme.adminlte.layouts.app')

@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Manage Variants - {{ $product->translation()?->name }}</h1>
    </div>
    <div class="col-sm-6">
      <a href="{{ route('admin.catalog.products.index') }}" class="btn btn-secondary float-sm-right">Back to Products</a>
    </div>
  </div>
@endsection

@section('content')
  <form method="POST" action="{{ route('admin.catalog.products.variants.store', $product) }}" class="ajax-form">
    @csrf
    <div class="card">
      <div class="card-body">
        <div id="variant-repeater">
          @foreach ($product->variants as $variant)
            <div class="variant-row row mb-3">
              @foreach ($attributes as $attribute)
                <div class="col-md-2">
                  <select name="variants[{{ $loop->parent->index ?? 0 }}][attributes][{{ $attribute->id }}]" class="form-control" required>
                    <option value="">Select {{ $attribute->name }}</option>
                    @foreach ($attribute->values as $value)
                      <option value="{{ $value->id }}"
                        @if ($variant->attributeValues->contains('id', $value->id)) selected @endif>
                        {{ $value->value }}
                      </option>
                    @endforeach
                  </select>
                </div>
              @endforeach

              <div class="col-md-2">
                <input type="number" name="variants[{{ $loop->index ?? 0 }}][price]" class="form-control" placeholder="Price" value="{{ $variant->price }}" required>
              </div>
              <div class="col-md-2">
                <input type="number" name="variants[{{ $loop->index ?? 0 }}][stock]" class="form-control" placeholder="Stock" value="{{ $variant->stock }}" required>
              </div>
              <div class="col-md-1">
                <button type="button" class="btn btn-danger remove-variant">×</button>
              </div>
            </div>
          @endforeach
        </div>

        <button type="button" class="btn btn-info mt-3" id="add-variant">Add Variant</button>
      </div>

      <div class="card-footer">
        <button type="submit" class="btn btn-primary">Save Variants</button>
      </div>
    </div>
  </form>
@endsection

@push('scripts')
  <script>
    let variantIndex = {{ $product->variants->count() }};

    $('#add-variant').on('click', function () {
      let row = `<div class="variant-row row mb-3">`;

      @foreach ($attributes as $attribute)
        row += `
        <div class="col-md-2">
          <select name="variants[${variantIndex}][attributes][{{ $attribute->id }}]" class="form-control" required>
            <option value="">Select {{ $attribute->name }}</option>
            @foreach ($attribute->values as $value)
              <option value="{{ $value->id }}">{{ $value->value }}</option>
            @endforeach
          </select>
        </div>`;
      @endforeach

      row += `
        <div class="col-md-2">
          <input type="number" name="variants[${variantIndex}][price]" class="form-control" placeholder="Price" required>
        </div>
        <div class="col-md-2">
          <input type="number" name="variants[${variantIndex}][stock]" class="form-control" placeholder="Stock" required>
        </div>
        <div class="col-md-1">
          <button type="button" class="btn btn-danger remove-variant">×</button>
        </div>
      </div>`;

      $('#variant-repeater').append(row);
      variantIndex++;
    });

    $(document).on('click', '.remove-variant', function () {
      $(this).closest('.variant-row').remove();
    });
  </script>
@endpush
