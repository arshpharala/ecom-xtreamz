@extends('theme.adminlte.layouts.app')
@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Return Reasons</h1>
    </div>
    <div class="col-sm-6 d-flex flex-row justify-content-end gap-2">
        <button data-url="{{ route('admin.sales.return-reasons.create') }}" type="button" class="btn btn-secondary"
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
            <th>Reason</th>
            <th>Status</th>
            <th>Created At</th>
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
        ajax: '{{ route('admin.sales.return-reasons.index') }}',
        columns: [
          {
            data: 'reason',
            name: 'reason'
          },
          {
            data: 'is_active',
            name: 'is_active'
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
