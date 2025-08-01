{{ Form::model($country, ['route' => ['admin.cms.countries.update', ['country' => $country]], 'method' => 'PUT', 'class' => 'ajax-form', 'files' => true, 'onsubmit' => 'handleFormSubmission(this)']) }}

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
          {{ Form::text('code', null, ['class' => 'form-control', 'required']) }}
        </div>
        <div class="form-group">
          <label>Name</label>
          {{ Form::text('name', null, ['class' => 'form-control', 'required']) }}
        </div>

        <div class="form-group">
          <label>Currency</label>
          {{ Form::select('currency_id', $currencies, null, ['class' => 'form-control', 'required']) }}
        </div>

        <div class="form-group">
          <label>Tax Label</label>
          {{ Form::select('tax_label', ['VAT' => 'VAT', 'TAX' => 'TAX', 'GST' => 'GST'], null, ['class' => 'form-control', 'required']) }}
        </div>

        <div class="form-group">
          <label>Tax Percentage</label>
          {{ Form::number('tax_percentage', null, ['class' => 'form-control', 'steps' => '0.2', 'required']) }}
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
{{ Form::close() }}
<script>
  $(document).ready(function() {
    $("form.ajax-form").each(function() {
      handleFormSubmission(this);
    });
  });
</script>
