@extends('theme.adminlte.layouts.app')
@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Brands</h1>
    </div>
    <div class="col-sm-6 d-flex flex-row justify-content-end gap-2">
      <button data-url="{{ route('admin.catalog.brands.create') }}" type="button" class="btn btn-primary"
        onclick="getAside()">Create Brand</button>
    </div>
  </div>
@endsection
@section('content')
  <div class="card">
    <div class="card-body">
      <table class="table table-bordered data-table">
        <thead>
          <tr>
            {{-- <th>#</th> --}}
            <th>Name</th>
            <th>Slug</th>
            <th>Logo</th>
            <th>Status</th>
            <th>Position</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
@endsection
@push('scripts')
  <script>
    $(function() {
      $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.catalog.brands.index') }}',
        columns: [
          //     {
          //     data: 'id',
          //     name: 'id'
          //   },
          {
            data: 'name',
            name: 'name'
          },
          {
            data: 'slug',
            name: 'slug'
          },
          {
            data: 'logo',
            name: 'logo',
            orderable: false,
            searchable: false
          },
          {
            data: 'is_active',
            name: 'is_active'
          },
          {
            data: 'position',
            name: 'position'
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
