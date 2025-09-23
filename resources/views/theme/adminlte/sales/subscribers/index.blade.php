@extends('theme.adminlte.layouts.app')
@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>@lang('crud.list_title', ['name' => 'Subscriber'])</h1>
    </div>

  </div>
@endsection
@section('content')
  <div class="card">
    <div class="card-body">
      <table class="table table-bordered data-table">
        <thead>
          <tr>
            {{-- <th>#</th> --}}
            <th>Email</th>
            <th>Status</th>
            <th>Subscribed At</th>
            <th>IP</th>
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
        ajax: '{{ route('admin.sales.subscribers.index') }}',
        columns: [
          {
            data: 'email',
            name: 'email'
          },
          {
            data: 'status',
            name: 'status',
            orderable: false,
            searchable: false
          },
          {
            data: 'subscribed_at',
            name: 'subscribed_at'
          },
          {
            data: 'ip_address',
            name: 'ip_address'
          }
        ]
      });
    });
  </script>
@endpush
