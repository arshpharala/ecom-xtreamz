@extends('theme.adminlte.layouts.app')
@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Return Requests</h1>
    </div>
  </div>
@endsection
@section('content')
  <div class="card">
    <div class="card-body">
      <table class="table table-bordered data-table">
        <thead>
          <tr>
            <th>Reference</th>
            <th>Customer</th>
            <th>Order</th>
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
        ajax: '{{ route('admin.sales.return-requests.index') }}',
        columns: [{
            data: 'reference_number',
            name: 'reference_number'
          },
          {
            data: 'user_name',
            name: 'user.name'
          },
          {
            data: 'order_ref',
            name: 'order.reference_number'
          },
          {
            data: 'reason.reason',
            name: 'reason.reason'
          },
          {
            data: 'status',
            name: 'status'
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
