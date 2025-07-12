@extends('theme.adminlte.layouts.app')

@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="m-0">Create Pages</h1>
    </div>
    <div class="col-sm-6">
      <a href="{{ route('admin.cms.pages.index') }}" class="btn btn-secondary float-sm-right">
        Back to List
      </a>
    </div>
  </div>
@endsection

@section('content')
  @php
    $locales = active_locals();
  @endphp
  <form action="{{ route('admin.cms.pages.store') }}" method="post" class="ajax-form">
    @csrf
    <div class="row">

      <div class="col-md-8">
        @foreach ($locales as $locale)
          <div class="card">
            <div class="card-body">
              <div class="form-group">
                <label for="title_{{ $locale }}">Title ({{ strtoupper($locale) }})</label>
                <input type="text" name="title[{{ $locale }}]" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="content_{{ $locale }}">Content ({{ strtoupper($locale) }})</label>
                <textarea name="content[{{ $locale }}]" class="form-control tinymce-editor" rows="15"></textarea>
              </div>

            </div>
          </div>
        @endforeach
      </div>

      <div class="col-md-4">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">General</h3>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label for="position">Slug</label>
              <input type="text" name="slug" class="form-control" required>
            </div>

            <div class="form-group">
              <label for="position">Position</label>
              <input type="number" name="position" class="form-control">

            </div>
            <div class="form-group">
              <div class="custom-control custom-switch mb-2">
                <input type="checkbox" name="is_active" value="1" class="custom-control-input" id="is_active">
                <label class="custom-control-label" for="is_active">Active</label>
              </div>
            </div>
          </div>
        </div>

        @include('theme.adminlte.components._metas', ['model' => $page, 'grid' => 'col-md-12'])
      </div>

      <div class="col-md-12 mb-4">
        <button type="submit" class="btn btn-secondary">Save Page</button>
      </div>
    </div>
  </form>
@endsection

@push('scripts')
  <script></script>
@endpush
