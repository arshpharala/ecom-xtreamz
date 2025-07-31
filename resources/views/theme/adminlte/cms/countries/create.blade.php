<form action="{{ route('admin.cms.countries.store') }}" method="post" class="ajax-form" enctype="multipart/form-data"
  onsubmit="handleFormSubmission(this)">
  @csrf

  @include('theme.adminlte.components._aside-header', [
      'moduleName' => __('crud.create_title', ['name' => 'Country']),
  ])

  <!-- Scrollable Content -->
  <div class="flex-fill" style="overflow-y:auto; min-height:0; max-height:calc(100vh - 132px);">
    <div class="p-3" id="aside-inner-content">

      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label>Code</label>
            <input type="text" name="code" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Currency</label>
            <select name="currency_id" class="form-control" required>
              @foreach ($currencies as $currency)
                <option value="{{ $currency->id }}">{{ $currency->code }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label>Icon</label>
            <input type="file" name="icon" class="form-control" accept="image/*">
          </div>

        </div>
      </div>

    </div>
  </div>

  <!-- Fixed Buttons -->
  @include('theme.adminlte.components._aside-footer')
</form>
<script>
  $(document).ready(function() {
    $("form.ajax-form").each(function() {
      handleFormSubmission(this);
    });
  });
</script>
