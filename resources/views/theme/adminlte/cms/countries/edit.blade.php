<form action="{{ route('admin.cms.countries.update', $country) }}" method="post" class="ajax-form"
  enctype="multipart/form-data" onsubmit="handleFormSubmission(this)">
  @csrf
  @method('PUT')

  @include('theme.adminlte.components._aside-header', [
      'moduleName' => __('crud.edit_title', ['name' => 'Country']),
  ])

  <!-- Scrollable Content -->
  <div class="flex-fill" style="overflow-y:auto; min-height:0; max-height:calc(100vh - 132px);">
    <div class="p-3" id="aside-inner-content">

      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label>Code</label>
            <input type="text" name="code" value="{{ $country->code }}" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="{{ $country->name }}" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Currency</label>
            <select name="currency_id" class="form-control" required>
              @foreach ($currencies as $currency)
                <option value="{{ $currency->id }}" @selected($currency->id == $country->currency_id)>{{ $currency->code }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label>Icon</label>
            <input type="file" name="icon" class="form-control" accept="image/*">
            @if (isset($currency) && $currency->icon)
              <div class="mt-2">
                <img src="{{ asset('storage/' . $currency->icon) }}" class="img-lg img-thumbnail">
              </div>
            @endif

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
