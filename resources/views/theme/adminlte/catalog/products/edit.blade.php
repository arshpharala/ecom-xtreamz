@extends('theme.adminlte.layouts.app')
@push('head')
  <link rel="stylesheet" href="{{ asset('assets/css/image-upload.css') }}">
@endpush

@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Edit Product</h1>
    </div>
    <div class="col-sm-6 d-flex flex-row justify-content-end gap-2">
      <button type="button" class="btn btn-outline-secondary" id="add-variant-row" onclick="getAside()"
        data-url="{{ route('admin.catalog.product.variants.create', $product->id) }}"> <i class="fa fa-plus"></i> Add
        Variant</button>
      <a href="{{ route('admin.catalog.products.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
  </div>
@endsection

@section('content')
  @php
    $locales = active_locals();
  @endphp

  <form method="POST" action="{{ route('admin.catalog.products.update', $product->id) }}" class="ajax-form"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
      <div class="col-md-8">
        <div class="card card-secondary">
          <div class="card-header">
            <h3 class="card-title">Product Details</h3>
          </div>
          <div class="card-body">
            {{-- Category Select --}}
            <div class="form-group">
              <label for="category_id">Category</label>
              <select name="category_id" id="category_id" class="form-control" disabled required>
                <option value="">Select Category</option>
                @foreach ($categories as $cat)
                  <option value="{{ $cat->id }}"
                    {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                    {{ $cat->translations->where('locale', app()->getLocale())->first()?->name ?? $cat->slug }}
                  </option>
                @endforeach
              </select>
            </div>

            {{-- Brand Select --}}
            <div class="form-group">
              <label for="brand_id">Brand</label>
              <select name="brand_id" class="form-control">
                <option value="">None</option>
                @foreach ($brands as $brand)
                  <option value="{{ $brand->id }}"
                    {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                    {{ $brand->name }}
                  </option>
                @endforeach
              </select>

            </div>

            {{-- Slug --}}
            <div class="form-group">
              <label for="slug">Slug</label>
              <input type="text" name="slug" class="form-control" value="{{ old('slug', $product->slug) }}"
                required>
            </div>

            {{-- Name and Description fields for ALL LANGUAGES --}}
            @foreach ($locales as $locale)
              <div class="form-group">
                <label for="name_{{ $locale }}">Name ({{ strtoupper($locale) }})</label>
                <input type="text" name="name[{{ $locale }}]" class="form-control"
                  value="{{ old("name.$locale", $product->translations->where('locale', $locale)->first()?->name) }}"
                  required>
              </div>
              <div class="form-group">
                <label for="description_{{ $locale }}">Description ({{ strtoupper($locale) }})</label>
                <textarea name="description[{{ $locale }}]" class="form-control" rows="3">{{ old("description.$locale", $product->translations->where('locale', $locale)->first()?->description) }}</textarea>
              </div>
            @endforeach
          </div>
        </div>

        @include('theme.adminlte.components._metas', ['model' => $product])

      </div>
      <div class="col-md-4">

        <div class="card card-secondary">
          <div class="card-header">
            <h3 class="card-title">Options</h3>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label for="position">Position</label>
              <input type="number" name="position" class="form-control"
                value="{{ old('position', $product->position) }}">

            </div>
            <div class="form-group">
              <div class="custom-control custom-switch mb-2">
                <input type="checkbox" name="is_active" value="1" class="custom-control-input" id="is_active"
                  {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                <label class="custom-control-label" for="is_active">Active</label>
              </div>
              <div class="custom-control custom-switch mb-2">
                <input type="checkbox" name="is_featured" value="1" class="custom-control-input" id="is_featured"
                  {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                <label class="custom-control-label" for="is_featured">Featured</label>
              </div>
              <div class="custom-control custom-switch mb-2">
                <input type="checkbox" name="is_new" value="1" class="custom-control-input" id="is_new"
                  {{ old('is_new', $product->is_new) ? 'checked' : '' }}>
                <label class="custom-control-label" for="is_new">New Arrival</label>
              </div>
              <div class="custom-control custom-switch mb-2">
                <input type="checkbox" name="show_in_slider" value="1" class="custom-control-input"
                  id="show_in_slider" {{ old('show_in_slider', $product->show_in_slider) ? 'checked' : '' }}>
                <label class="custom-control-label" for="show_in_slider">Show in Slider</label>
              </div>
            </div>
          </div>
        </div>

        <div id="product-variants">

        </div>


      </div>
    </div>
    <div class="mt-3">
      <button type="submit" class="btn btn-primary">Update Product</button>
    </div>
  </form>

  @push('scripts')
    <script src="{{ asset('assets/js/image-upload.js') }}"></script>
  @endpush

  @push('scripts')
    <script>
      $(function() {
        $.get(`{{ route('admin.catalog.product.variants.index', $product) }}`, function(res) {
          $('#product-variants').html(res.data?.view || '');
        }).fail(function() {
          $('#product-variants').html('<div class="alert alert-danger">Failed to load variants.</div>');
        });
      });
    </script>
  @endpush

  @push('scripts')
    <script>
      $('#category_id').change(function() {
        let categoryId = $(this).val();
        if (!categoryId) {
          window.setVariantAttributes([]);
          return;
        }
        $.get("{{ url('admin/catalog/category') }}/" + categoryId + "/attributes", function(attrs) {
          window.setVariantAttributes(attrs);
        });
      });
    </script>
  @endpush
@endsection
