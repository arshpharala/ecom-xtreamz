@extends('theme.adminlte.layouts.app')
@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="m-0">@lang('crud.list_title', ['name' => 'Coupon'])</h1>
    </div>
    <div class="col-sm-6">
      @can('create', App\Models\Cart\Coupon::class)
        <a href="{{ route('admin.catalog.coupons.create') }}" class="btn btn-secondary float-sm-right"> <i
            class="fa fa-plus"></i> @lang('crud.create')</a>
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
                  <th>Type</th>
                  <th>Value</th>
                  <th>Start</th>
                  <th>End</th>
                  <th>Active</th>
                  <th>Created At</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>

              </tbody>
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
        ajax: '{{ route('admin.catalog.coupons.index') }}',
        columns: [{
            data: 'code',
            orderable: false,
            searchable: false
          },
          {
            data: 'type',
            name: 'type',
            class: "text-uppercase"
          },
          {
            data: 'value',
            name: 'value'
          },
          {
            data: 'start_at',
            name: 'start_at'
          },
          {
            data: 'end_at',
            name: 'end_at'
          },
          {
            data: 'is_active',
            orderable: false,
            searchable: false
          },
          {
            data: 'created_at',
            name: 'created_at'
          },
          {
            data: 'action',
            orderable: false,
            searchable: false
          }
        ]
      });

    });
  </script>
@endpush
