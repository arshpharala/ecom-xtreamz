@extends('theme.adminlte.layouts.app')

@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>API Sync Logs</h1>
    </div>
  </div>
@endsection

@section('content')
  <div class="card">
    <div class="card-body">
      <table class="table table-bordered data-table">
        <thead>
          <tr>
            <th>Source</th>
            <th>Endpoint</th>
            <th>Total Records</th>
            <th>Status</th>
            <th>HTTP</th>
            <th>Fetched At</th>
            <th>Message</th>
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
      ajax: '{{ route('admin.cms.api-sync-logs.index') }}',
      order: [[5, 'desc']],
      columns: [
        { data: 'source', name: 'source' },
        { data: 'endpoint', name: 'endpoint' },
        { data: 'total_records', name: 'total_records' },
        { data: 'success', name: 'success', orderable: false, searchable: false },
        { data: 'http_status', name: 'http_status' },
        { data: 'fetched_at', name: 'fetched_at' },
        { data: 'message', name: 'message' }
      ]
    });
  });
</script>
@endpush
