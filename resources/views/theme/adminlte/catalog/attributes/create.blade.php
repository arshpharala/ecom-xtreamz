@extends('theme.adminlte.layouts.app')

@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Create Attribute</h1>
    </div>
    <div class="col-sm-6">
      <a href="{{ route('admin.catalog.attributes.index') }}" class="btn btn-secondary float-sm-right">
        Back to List
      </a>
    </div>
  </div>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-5 col-sm-12">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Attribute Details</h3>
        </div>

        <form action="{{ route('admin.catalog.attributes.store') }}" method="POST" class="ajax-form">
          @csrf
          <div class="card-body">
            {{-- Attribute Name --}}
            <div class="form-group">
              <label for="name">Attribute Name</label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name"
                value="{{ old('name') }}" required>
              @error('name')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            {{-- Attribute Values --}}
            <label>Attribute Values</label>
            <div id="value-repeater">
              <div class="form-group d-flex gap-2 mb-2">
                <input type="text" name="values[]" class="form-control" placeholder="Enter value" required>
                <button type="button" class="btn btn-danger remove-value">×</button>
              </div>
            </div>
            <button type="button" class="btn btn-sm btn-info" id="add-value">Add Value</button>
          </div>

          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Create Attribute</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    $(document).ready(function() {
      $('#add-value').on('click', function() {
        $('#value-repeater').append(`
        <div class="form-group d-flex gap-2 mb-2">
          <input type="text" name="values[]" class="form-control" placeholder="Enter value" required>
          <button type="button" class="btn btn-danger remove-value">×</button>
        </div>
      `);
      });

      $(document).on('click', '.remove-value', function() {
        $(this).closest('.form-group').remove();
      });
    });
  </script>
@endpush
