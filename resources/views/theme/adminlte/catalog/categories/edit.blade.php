@extends('theme.adminlte.layouts.app')

@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>@lang('crud.edit_title', ['name' => 'Category'])</h1>
    </div>
    <div class="col-sm-6">
      <a href="{{ route('admin.catalog.categories.index') }}" class="btn btn-secondary float-sm-right">
        @lang('crud.back_to_list', ['name' => 'Category'])
      </a>
    </div>
  </div>
@endsection

@section('content')
  <form action="{{ route('admin.catalog.categories.update', $category->id) }}" method="POST" class="ajax-form"
    enctype="multipart/form-data">
    <div class="row">
      {{-- Main Content --}}
      <div class="col-md-8">
        <div class="card card-secondary">
          <div class="card-header">
            <h3 class="card-title">Edit Category</h3>
          </div>
          @csrf
          @method('PUT')
          <div class="card-body">

            {{-- Slug --}}
            <div class="form-group">
              <label for="slug">Slug</label>
              <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                value="{{ old('slug', $category->slug) }}" required>
              @error('slug')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>


            {{-- Parent Category --}}
            <div class="form-group">
              <label for="parent_id">Parent Category (optional)</label>
              <select name="parent_id" class="form-control @error('parent_id') is-invalid @enderror">
                <option value="">-- None --</option>
                @foreach ($categories as $cat)
                  <option value="{{ $cat->id }}"
                    {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>
                    {{ $cat->translations->first()->name ?? $cat->slug }}
                  </option>
                @endforeach
              </select>
              @error('parent_id')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            {{-- Name Translations --}}
            @foreach (active_locals() as $locale)
              <div class="form-group">
                <label for="name_{{ $locale }}">Name ({{ strtoupper($locale) }})</label>
                <input type="text" name="name[{{ $locale }}]"
                  class="form-control @error("name.$locale") is-invalid @enderror"
                  value="{{ old("name.$locale", $category->translations->where('locale', $locale)->first()?->name) }}"
                  required>
                @error("name.$locale")
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
            @endforeach

            {{-- Icon --}}
            <div class="form-group">
              <label>Icon</label>

              <input type="file" name="icon" class="form-control" accept="image/*">

              @if (isset($category) && $category->icon)
                <div class="mt-2">
                  <img src="{{ asset('storage/' . $category->icon) }}" class="img-lg img-thumbnail">
                </div>
              @endif
            </div>

          </div>
          <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary">@lang('crud.create')</button>
          </div>
        </div>


      </div>

      {{-- Sidebar Card (Meta/Options) --}}
      <div class="col-md-4">
        <div class="card card-secondary">
          <div class="card-header">
            <h3 class="card-title">Category Options</h3>
          </div>
          <div class="card-body">

            {{-- Position --}}
            <div class="form-group">
              <label for="position">Position</label>
              <input type="number" name="position" class="form-control @error('position') is-invalid @enderror"
                value="{{ old('position', $category->position) }}">
              @error('position')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            {{-- Is Visible --}}
            <div class="form-group">
              <div class="custom-control custom-switch">
                <input type="checkbox" name="is_visible" value="1" class="custom-control-input" id="is_visible"
                  {{ old('is_visible', $category->is_visible) ? 'checked' : '' }}>
                <label class="custom-control-label" for="is_visible">Visible in Store</label>
              </div>
            </div>

            {{-- Filterable Attributes --}}
            <div class="form-group">
              <label>Filterable Attributes</label>
              <div>
                @foreach ($attributes as $attribute)
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="attributes[]"
                      id="attribute_{{ $attribute->id }}" value="{{ $attribute->id }}"
                      @php $selected = old('attributes', $category->attributes->pluck('id')->toArray()); @endphp
                      {{ in_array($attribute->id, $selected) ? 'checked' : '' }}>
                    <label class="form-check-label" for="attribute_{{ $attribute->id }}">
                      {{ $attribute->name }}
                    </label>
                  </div>
                @endforeach
              </div>
              <small class="form-text text-muted">Check attributes to be available as filters.</small>
              @error('attributes')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

          </div>
        </div>

        @include('theme.adminlte.components._metas', ['model' => $category, 'grid' => 'col-md-12 col-12'])
      </div>
    </div>
  </form>
@endsection
