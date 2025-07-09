@extends('theme.adminlte.layouts.app')

@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6"><h1>Edit Product</h1></div>
    <div class="col-sm-6">
      <a href="{{ route('admin.catalog.products.index') }}" class="btn btn-secondary float-sm-right">Back to List</a>
    </div>
  </div>
@endsection

@section('content')
@php
  $locales = active_locals();
@endphp

<form method="POST" action="{{ route('admin.catalog.products.update', $product->id) }}" class="ajax-form" enctype="multipart/form-data">
  @csrf
  @method('PUT')
  <div class="row">
    <div class="col-md-8">
      <div class="card card-primary">
        <div class="card-header"><h3 class="card-title">Product Details</h3></div>
        <div class="card-body">
          {{-- Slug --}}
          <div class="form-group">
            <label for="slug">Slug</label>
            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                   value="{{ old('slug', $product->slug) }}" required>
            @error('slug') <span class="text-danger">{{ $message }}</span> @enderror
          </div>
          {{-- Category Select --}}
          <div class="form-group">
            <label for="category_id">Category</label>
            <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
              <option value="">Select Category</option>
              @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                  {{ $cat->translations->where('locale', app()->getLocale())->first()?->name ?? $cat->slug }}
                </option>
              @endforeach
            </select>
            @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
          </div>
          {{-- Brand Select --}}
          <div class="form-group">
            <label for="brand_id">Brand</label>
            <select name="brand_id" class="form-control @error('brand_id') is-invalid @enderror">
              <option value="">None</option>
              @foreach ($brands as $brand)
                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                  {{ $brand->translations->where('locale', app()->getLocale())->first()?->name ?? $brand->name }}
                </option>
              @endforeach
            </select>
            @error('brand_id') <span class="text-danger">{{ $message }}</span> @enderror
          </div>
          {{-- Name and Description fields for ALL LANGUAGES --}}
          @foreach ($locales as $locale)
            <div class="form-group">
              <label for="name_{{ $locale }}">Name ({{ strtoupper($locale) }})</label>
              <input type="text"
                     name="name[{{ $locale }}]"
                     class="form-control"
                     value="{{ old("name.$locale", $product->translations->where('locale', $locale)->first()?->name) }}"
                     required>
              @error("name.$locale")
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
            <div class="form-group">
              <label for="description_{{ $locale }}">Description ({{ strtoupper($locale) }})</label>
              <textarea name="description[{{ $locale }}]"
                        class="form-control"
                        rows="3">{{ old("description.$locale", $product->translations->where('locale', $locale)->first()?->description) }}</textarea>
              @error("description.$locale")
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
          @endforeach
        </div>
      </div>
      {{-- VARIANT MANAGER --}}
      @include('theme.adminlte.components._variant-manager', [
          'categoryAttributes' => $categoryAttributes,
          'variants' => old('variants', $variants)
      ])
      <div class="card card-default mt-3">
        <div class="card-header"><h3 class="card-title">Attachments</h3></div>
        <div class="card-body">
          <input type="file" name="attachments[]" multiple class="form-control">
          @if($product->attachments && $product->attachments->count())
            <div class="mt-2">
              @foreach($product->attachments as $attachment)
                <a href="{{ asset('storage/'.$attachment->file_path) }}" target="_blank">
                  {{ $attachment->file_name ?? basename($attachment->file_path) }}
                </a>
                <br>
              @endforeach
            </div>
          @endif
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card card-secondary">
        <div class="card-header"><h3 class="card-title">Options</h3></div>
        <div class="card-body">
          <div class="form-group">
            <label for="position">Position</label>
            <input type="number" name="position" class="form-control" value="{{ old('position', $product->position) }}">
            @error('position') <span class="text-danger">{{ $message }}</span> @enderror
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
              <input type="checkbox" name="show_in_slider" value="1" class="custom-control-input" id="show_in_slider"
                     {{ old('show_in_slider', $product->show_in_slider) ? 'checked' : '' }}>
              <label class="custom-control-label" for="show_in_slider">Show in Slider</label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="mt-3">
    <button type="submit" class="btn btn-primary">Update Product</button>
  </div>
</form>

@push('scripts')
<script>
$('#category_id').change(function() {
    let categoryId = $(this).val();
    if (!categoryId) {
        window.setVariantAttributes([]);
        return;
    }
    $.get("{{ url('admin/catalog/category') }}/" + categoryId + "/attributes", function(attrs){
        window.setVariantAttributes(attrs);
    });
});
</script>
@endpush
@endsection
