@extends('theme.adminlte.layouts.app')
@section('content-header')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="m-0">@lang('crud.create_title', ['name' => 'Product'])</h1>
    </div>
    <div class="col-sm-6 d-flex flex-row justify-content-end gap-2">
      <button data-url="{{ route('admin.catalog.products.create') }}" type="button" class="btn btn-secondary"
        onclick="getAside()"> <i class="fa fa-plus"></i> @lang('crud.create')</button>
    </div>
  </div>
@endsection
@section('content')
  <div class="row">
    <div class="col-md-12">
      <form action="" method="get" id="filter-form">

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Filter</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <!-- Category Filter -->
              <div class="col-md-3 col-lg-2 form-group">
                <label for="category">Category</label>
                {{ Form::select('category_id[]', $categories, request('category_id'), ['class' => 'form-control select2', 'multiple', 'data-placeholder' => 'All Categories', 'id' => 'category']) }}
              </div>

              <!-- Brand Filter -->
              <div class="col-md-3 col-lg-2 form-group">
                <label for="brand">Brand</label>
                {{ Form::select('brand_id', $brands, request('brand_id'), ['class' => 'form-control select2', 'multiple', 'data-placeholder' => 'All Brands', 'id' => 'brand']) }}
              </div>

              <!-- Status Filter -->
              <div class="col-md-3 col-lg-2 form-group">
                <label for="status">Status</label>
                {{ Form::select(
                    'status',
                    [
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'deleted' => 'Deleted',
                    ],
                    request('status'),
                    ['class' => 'form-control select2', 'id' => 'status', 'placeholder' => 'All', 'data-placeholder' => 'All'],
                ) }}
                </select>
              </div>
            </div>

          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary" id="filter-btn">Filter</button>
            <a href="{{ url()->current() }}" type="button" class="btn btn-secondary">Reset</a>
          </div>
        </div>

      </form>
    </div>
  </div>

  {{-- <div class="mb-2">
    <button type="button" class="btn btn-danger btn-sm" id="bulk-delete">Delete Selected</button>
    <button type="button" class="btn btn-success btn-sm" id="bulk-restore">Restore Selected</button>
  </div> --}}
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
                  <th>Category</th>
                  <th>Brand</th>
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
      let filterForm = $('#filter-form');

      let table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '{{ route('admin.catalog.products.index') }}',
          data: d => $.extend(d, getFormFilters(filterForm, true))
        },
        columns: [{
            data: 'id',
            orderable: false,
            searchable: false,
            render: data => `<input type="checkbox" class="row-checkbox" value="${data}">`
          },
          {
            data: 'name',
            name: 'product_translations.name'
          },
          {
            data: 'slug',
            name: 'slug'
          },
          {
            data: 'category_name',
            name: 'category_translations.name'
          },
          {
            data: 'brand_name',
            name: 'brands.name'
          },
          {
            data: 'status',
            name: 'products.is_active'
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


      filterForm.on('submit', function(e) {
        e.preventDefault();
        table.ajax.reload();
      });

    });
  </script>
  <script>
    $(function() {

      $('#select-all').on('click', function() {
        $('.row-checkbox').prop('checked', this.checked);
      });

      $('#bulk-delete').on('click', function() {
        let ids = $('.row-checkbox:checked').map(function() {
          return $(this).val();
        }).get();
        if (ids.length === 0) return alert('No products selected!');
        if (!confirm('Delete selected products?')) return;
        $.post("{{ route('admin.catalog.products.bulk-delete') }}", {
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
        if (ids.length === 0) return alert('No products selected!');
        $.post("{{ route('admin.catalog.products.bulk-restore') }}", {
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
