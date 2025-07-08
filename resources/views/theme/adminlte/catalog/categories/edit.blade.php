@extends('theme.adminlte.layouts.app')

@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Edit Category</h1>
    </div>
    <div class="col-sm-6">
      <a href="{{ route('admin.catalog.categories.index') }}" class="btn btn-secondary float-sm-right">
        Back to List
      </a>
    </div>
  </div>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-8">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Edit Category</h3>
        </div>
        <form action="{{ route('admin.catalog.categories.update', $category) }}" method="POST" class="ajax-form">
          @csrf
          @method('PUT')
          <div class="card-body">
            {{-- Slug --}}
            <div class="form-group">
              <label for="slug">Slug</label>
              <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                     id="slug" value="{{ old('slug', $category->slug) }}" required>
              @error('slug')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            {{-- Name Translations --}}
            @foreach (active_locals() as $locale)
              <div class="form-group">
                <label for="name_{{ $locale }}">Name ({{ strtoupper($locale) }})</label>
                <input type="text" name="name[{{ $locale }}]"
                       class="form-control @error("name.$locale") is-invalid @enderror"
                       id="name_{{ $locale }}"
                       value="{{ old("name.$locale", $category->translations->where('locale', $locale)->first()?->name) }}"
                       required>
                @error("name.$locale")
                  <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
            @endforeach
          </div>

          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Category</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
