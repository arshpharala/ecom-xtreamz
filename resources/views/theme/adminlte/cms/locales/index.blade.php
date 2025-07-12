@extends('theme.adminlte.layouts.app')

@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="m-0">Locale</h1>
    </div>
    <div class="col-sm-6">
      <button type="button" onclick="getAside()" data-url="{{ route('admin.cms.locales.create') }}" class="btn btn-primary float-sm-right">Add Locale</button>
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
                  <th>Code</th>
                  <th>Name</th>
                  <th>Direction</th>
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
        ajax: '{{ route('admin.cms.locales.index') }}',
        columns: [
          {
            data: 'code',
            name: 'code'
          },
          {
            data: 'name',
            name: 'name'
          },
          {
            data: 'direction',
            name: 'direction'
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
