@extends('theme.adminlte.layouts.app')
@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>@lang('crud.list_title', ['name' => 'Email'])</h1>
    </div>
    <div class="col-sm-6 d-flex flex-row justify-content-end gap-2">
      <button data-url="{{ route('admin.cms.emails.create') }}" type="button" class="btn btn-secondary"
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
            <th>Reference</th>
            <th>Template</th>
            <th>Status</th>
            <th>To</th>
            <th>CC</th>
            <th>BCC</th>
            <th>Exclude</th>
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
        ajax: '{{ route('admin.cms.emails.index') }}',
        columns: [{
            data: 'reference',
            name: 'reference'
          },
          {
            data: 'template',
            name: 'template',
          },
          {
            data: 'is_active',
            name: 'is_active',
          },
          {
            data: 'to_count',
            name: 'to_count'
          },
          {
            data: 'cc_count',
            name: 'cc_count'
          },
          {
            data: 'bcc_count',
            name: 'bcc_count'
          },
          {
            data: 'exclude_count',
            name: 'exclude_count'
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
