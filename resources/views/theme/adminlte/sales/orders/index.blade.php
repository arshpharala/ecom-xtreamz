@extends('theme.adminlte.layouts.app')

@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Orders</h1>
    </div>
  </div>
@endsection

@section('content')
  <div class="card">
    <div class="card-body">
      <table class="table table-bordered data-table">
        <thead>
          <tr>
            <th>Order #</th>
            <th>Customer</th>
            <th>Email</th>
            <th>Currency</th>
            <th>Total</th>
            <th>Order Status</th>
            <th>Payment Status</th>
            <th>Date</th>
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
        ajax: '{{ route('admin.sales.orders.index') }}',
        columns: [{
            data: 'reference_number',
            name: 'reference_number'
          },
          {
            data: 'user_name',
            name: 'users.name'
          },
          {
            data: 'user_email',
            name: 'users.email'
          },
          {
            data: 'currency_code',
            name: 'currencies.code'
          },
          {
            data: 'total',
            name: 'total'
          },
          {
            data: 'order_status',
            name: 'orders.status'
          },
          {
            data: 'payment_status',
            name: 'payment_status'
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
