@extends('theme.adminlte.layouts.app')

@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="m-0">Products</h1>
    </div>
    <div class="col-sm-6">
      <a href="{{ route('admin.catalog.products.create') }}" class="btn btn-primary float-sm-right">Create Product</a>
    </div>
  </div>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Slug</th>
                  <th>Category</th>
                  <th>Created At</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    $(function() {
      $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.catalog.products.index') }}',
        columns: [{
            data: 'id',
            name: 'id'
          },
          {
            data: 'name',
            name: 'translations.name',
            orderable: false,
            searchable: false
          },
          {
            data: 'slug',
            name: 'slug'
          },
          {
            data: 'category',
            name: 'category.translation.name',
            orderable: false,
            searchable: false
          },
          {
            data: 'created_at',
            name: 'created_at'
          },
          {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
          }
        ]
      });
    });
  </script>
@endpush
