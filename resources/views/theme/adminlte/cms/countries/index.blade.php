@extends('theme.adminlte.layouts.app')

@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="m-0">@lang('crud.list_title', ['name' => 'Country'])</h1>
    </div>
    <div class="col-sm-6">
      @can('create', App\Models\CMS\Country::class)
        <button type="button" onclick="getAside()" data-url="{{ route('admin.cms.countries.create') }}"
          class="btn btn-secondary float-sm-right"> <i class="fa fa-plus"></i> @lang('crud.create')</button>
      @endcan
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
                  <th>Currency</th>
                  <th>Tax Label</th>
                  <th>Tax Percentage</th>
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
        ajax: '{{ route('admin.cms.countries.index') }}',
        columns: [{
            data: 'code',
            name: 'code'
          },
          {
            data: 'name',
            name: 'name'
          },
          {
            data: 'currency_code',
            name: 'currencies.code'
          },
          {
            data: 'tax_label',
            name: 'tax_label'
          },
          {
            data: 'tax_percentage',
            name: 'tax_percentage'
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
