@extends('theme.adminlte.layouts.app')
@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="m-0">Categories</h1>
    </div>
    <div class="col-sm-6">
      <a href="{{ route('admin.catalog.categories.create') }}" class="btn btn-primary float-sm-right">Create Category</a>
    </div>
  </div>
@endsection
@section('content')
  <div class="mb-2">
    <button type="button" class="btn btn-danger btn-sm" id="bulk-delete">Delete Selected</button>
    <button type="button" class="btn btn-success btn-sm" id="bulk-restore">Restore Selected</button>
  </div>
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
                  <th>Slug</th>
                  <th>Status</th>
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
        ajax: '{{ route('admin.catalog.categories.index') }}',
        columns: [{
            data: 'id',
            orderable: false,
            searchable: false,
            render: data => `<input type="checkbox" class="row-checkbox" value="${data}">`
          },
        //   {
        //     data: 'id',
        //     name: 'id'
        //   },
          {
            data: 'name',
            orderable: false,
            searchable: false
          },
          {
            data: 'slug',
            name: 'slug'
          },
          {
            data: 'status',
            orderable: false,
            searchable: false
          },
          {
            data: 'created_at',
            name: 'created_at'
          },
          {
            data: 'action',
            orderable: false,
            searchable: false
          }
        ]
      });

      $('#select-all').on('click', function() {
        $('.row-checkbox').prop('checked', this.checked);
      });

      $('#bulk-delete').on('click', function() {
        let ids = $('.row-checkbox:checked').map(function() {
          return $(this).val();
        }).get();
        if (ids.length === 0) return alert('No categories selected!');
        if (!confirm('Delete selected categories?')) return;
        $.post("{{ route('admin.catalog.categories.bulk-delete') }}", {
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
        if (ids.length === 0) return alert('No categories selected!');
        $.post("{{ route('admin.catalog.categories.bulk-restore') }}", {
          ids,
          _token: "{{ csrf_token() }}"
        }, function(resp) {
          $('.data-table').DataTable().ajax.reload();
          alert(resp.message);
        });
      });
    });
  </script>
@endpush
