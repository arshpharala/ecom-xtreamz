@extends('theme.adminlte.layouts.app')

@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>@lang('crud.list_title', ['name' => 'Attribute'])</h1>
    </div>
    <div class="col-sm-6 d-flex flex-row justify-content-end gap-2">
      @can('create', App\Models\Catalog\Attribute::class)
        <a href="{{ route('admin.catalog.attributes.create') }}" class="btn btn-secondary"><i class="fa fa-plus"></i>
          @lang('crud.create')</a>
      @endcan
    </div>
  </div>
@endsection

@section('content')
  @can('bulk', App\Models\Catalog\Attribute::class)
    <div class="mb-2">
      <button type="button" class="btn btn-danger btn-sm" id="bulk-delete">Delete Selected</button>
      <button type="button" class="btn btn-success btn-sm" id="bulk-restore">Restore Selected</button>
    </div>
  @endcan
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th><input type="checkbox" id="select-all"></th>
                  {{-- <th>#</th> --}}
                  <th>Name</th>
                  <th>Created At</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody></tbody>
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
        ajax: '{{ route('admin.catalog.attributes.index') }}',
        columns: [{
            data: 'id',
            orderable: false,
            searchable: false,
            render: function(data, type, row) {
              return `<input type="checkbox" class="row-checkbox" value="${data}">`;
            }
          },
          //   {
          //     data: 'id',
          //     name: 'id'
          //   },
          {
            data: 'name',
            name: 'name'
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

  <script>
    $('#select-all').on('click', function() {
      $('.row-checkbox').prop('checked', this.checked);
    });

    $('#bulk-delete').on('click', function() {
      let ids = $('.row-checkbox:checked').map(function() {
        return $(this).val();
      }).get();
      if (ids.length === 0) return alert('No attributes selected!');
      if (!confirm('Delete selected attributes?')) return;
      $.post("{{ route('admin.catalog.attributes.bulk-delete') }}", {
        ids,
        _token: "{{ csrf_token() }}"
      }, function(resp) {
        $('.data-table').DataTable().ajax.reload();
        alert(resp.message);
      });
    });

    $('#bulk-restore').on('click', function() {
      let ids = $('.row-checkbox:checked').map(function() {
        return $(this).val();
      }).get();
      if (ids.length === 0) return alert('No attributes selected!');
      $.post("{{ route('admin.catalog.attributes.bulk-restore') }}", {
        ids,
        _token: "{{ csrf_token() }}"
      }, function(resp) {
        $('.data-table').DataTable().ajax.reload();
        alert(resp.message);
      });
    });
  </script>
@endpush
