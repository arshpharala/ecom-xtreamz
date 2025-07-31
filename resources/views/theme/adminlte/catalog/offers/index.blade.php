@extends('theme.adminlte.layouts.app')
@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>@lang('crud.list_title', ['name' => 'Offer'])</h1>
    </div>
    <div class="col-sm-6 d-flex flex-row justify-content-end gap-2">
      <button data-url="{{ route('admin.catalog.offers.create') }}" type="button" class="btn btn-secondary"
        onclick="getAside()"><i class="fa fa-plus"></i> @lang('crud.create')</button>
    </div>
  </div>
@endsection
@section('content')
  <div class="card">
    <div class="card-body">
      <table class="table table-bordered data-table">
        <thead>
          <tr>
            <th>Title</th>
            <th>Discount Type</th>
            <th>Discount Value</th>
            <th>Start At</th>
            <th>End At</th>
            <th>Status</th>
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
        ajax: '{{ route('admin.catalog.offers.index') }}',
        columns: [{
            data: 'title',
            name: 'title'
          },
          {
            data: 'discount_type',
            name: 'discount_type',
          },
          {
            data: 'discount_value',
            name: 'discount_value',
          },
          {
            data: 'starts_at',
            name: 'starts_at'
          },
          {
            data: 'ends_at',
            name: 'ends_at'
          },
          {
            data: 'is_active',
            name: 'is_active'
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
