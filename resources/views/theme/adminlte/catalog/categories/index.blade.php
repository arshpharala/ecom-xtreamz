@extends('theme.adminlte.layouts.app')
@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="m-0">@lang('crud.list_title', ['name' => 'Category'])</h1>
    </div>
    <div class="col-sm-6">
      @can('create', App\Models\Catalog\Category::class)
        <a href="{{ route('admin.catalog.categories.create') }}" class="btn btn-secondary float-sm-right"> <i
            class="fa fa-plus"></i> @lang('crud.create')</a>
      @endcan
    </div>
  </div>
@endsection
@section('content')
  @can('bulk', App\Models\Catalog\Category::class)
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
                  <th>Icon</th>
                  <th>Name</th>
                  <th>Slug</th>
                  <th>Visible in Store</th>
                  <th>Show on Homepage</th>
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
          {
            data: 'icon',
            name: 'icon',
            orderable: false,
            searchable: false
          },
          {
            data: 'name',
            name: 'category_translations.name'
          },
          {
            data: 'slug',
            name: 'slug'
          },
          {
            data: 'is_visible',
            name: 'is_visible'
          },
          {
            data: 'show_on_homepage',
            name: 'show_on_homepage'
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
