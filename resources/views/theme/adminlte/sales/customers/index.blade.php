@extends('theme.adminlte.layouts.app')
@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Customers</h1>
    </div>
  </div>
@endsection

@section('content')
  <div class="card">
    <div class="card-body">
      <table class="table table-bordered data-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Orders</th>
            <th>Total Spent</th>
            <th>Last Login</th>
            <th>Password Reset</th>
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
    $('.data-table').DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ route('admin.sales.customers.index') }}',
      columns: [{
          data: 'name'
        },
        {
          data: 'email'
        },
        {
          data: 'orders_count',
          searchable: false
        },
        {
          data: 'total_spent',
          searchable: false
        },
        {
          data: 'last_login_at',
          searchable: false
        },
        {
          data: 'password_changed_at',
          searchable: false
        },
        {
          data: 'is_active',
          orderable: false,
          searchable: false
        },
        {
          data: 'action',
          orderable: false,
          searchable: false
        },
      ]
    });
  </script>
@endpush
