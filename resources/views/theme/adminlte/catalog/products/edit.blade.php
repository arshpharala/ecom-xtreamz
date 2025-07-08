@extends('theme.adminlte.layouts.app')

@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Edit Product</h1>
    </div>
    <div class="col-sm-6">
      <a href="{{ route('admin.catalog.products.index') }}" class="btn btn-secondary float-sm-right">Back to List</a>
    </div>
  </div>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-8">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Update Product</h3>
        </div>

        <form method="POST" action="{{ route('admin.catalog.products.update', $product) }}" class="ajax-form">
          @csrf
          @method('PUT')
          <div class="card-body">
            {{-- Slug --}}
            <div class="form-group">
              <label for="slug">Slug</label>
              <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                value="{{ old('slug', $product->slug) }}" required>
              @error('slug')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            {{-- Category --}}
            <div class="form-group">
              <label for="category_id">Category</label>
              <select name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                <option value="">Select Category</option>
                @foreach ($categories as $cat)
                  <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>
                    {{ $cat->translation()?->name ?? 'Unnamed' }}
                  </option>
                @endforeach
              </select>
              @error('category_id')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            {{-- Translations --}}
            @php
              $translations = $product->translations->keyBy('locale');
            @endphp

            @foreach (active_locals() as $locale)
              <div class="form-group">
                <label for="name_{{ $locale }}">Name ({{ strtoupper($locale) }})</label>
                <input type="text" name="name[{{ $locale }}]" class="form-control"
                  value="{{ old("name.$locale", $translations[$locale]->name ?? '') }}" required>
              </div>

              <div class="form-group">
                <label for="description_{{ $locale }}">Description ({{ strtoupper($locale) }})</label>
                <textarea name="description[{{ $locale }}]" class="form-control" rows="3">{{ old("description.$locale", $translations[$locale]->description ?? '') }}</textarea>
              </div>
            @endforeach
          </div>

          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Product</button>
          </div>
        </form>
      </div>
    </div>

    <div class="col-md-4">
        @include('theme.adminlte.components.product-variant-manager', ['product' => $product, 'attributes' => $attributes])

    </div>
  </div>


@endsection
