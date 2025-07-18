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
            <th>Total</th>
            <th>Status</th>
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
  $(function () {
    $('.data-table').DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ route('admin.sales.orders.index') }}',
      columns: [
        { data: 'order_number', name: 'order_number' },
        { data: 'customer', name: 'billing_address.name', orderable: false },
        { data: 'email', name: 'email' },
        { data: 'total', name: 'total' },
        { data: 'status', name: 'payment_status' },
        { data: 'created_at', name: 'created_at' },
        { data: 'action', name: 'action', orderable: false, searchable: false }
      ]
    });
  });
</script>
@endpush
