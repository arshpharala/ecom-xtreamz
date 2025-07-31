@extends('theme.adminlte.layouts.app')

@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="m-0">@lang('crud.list_title', ['name' => 'Currency'])</h1>
    </div>
    <div class="col-sm-6">
      <button type="button" onclick="getAside()" data-url="{{ route('admin.cms.currencies.create') }}" class="btn btn-secondary float-sm-right"> <i class="fa fa-plus"></i> @lang('crud.create')</button>
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
                  <th>Currency Position</th>
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
        ajax: '{{ route('admin.cms.currencies.index') }}',
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
            data: 'currency_position',
            name: 'currency_position'
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
