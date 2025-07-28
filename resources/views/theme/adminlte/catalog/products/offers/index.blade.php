<form
  action="{{ route('admin.catalog.product.variant.offers.store', ['product' => $variant->product, 'variant' => $variant]) }}"
  method="POST" class="ajax-form" enctype="multipart/form-data" onsubmit="handleFormSubmission(this)">
  @csrf

  <!-- Header -->
  <div class="p-3 border-bottom bg-light">
    <div class="d-flex justify-content-between align-items-center">
      <h4 class="mb-0 text-dark font-weight-bold">Manage Offer</h4>
      <a data-widget="control-sidebar" data-slide="true" href="#" role="button">
        <i class="fa fa-times text-muted"></i>
      </a>
    </div>
  </div>

  <!-- Body -->
  <div class="flex-fill overflow-auto" style="max-height: calc(100vh - 132px);">
    <div class="p-3">

      <!-- Offer Dropdown -->
      <div class="form-group mb-4">
        <label class="font-weight-semibold">Select Offer to Link</label>
        <select name="offer_id" class="form-control" required>
          <option value="">-- Select Offer --</option>
          @foreach ($offers as $offer)
            <option value="{{ $offer->id }}">
              {{ $offer->title }}
              @if ($offer->discount_type === 'percent')
                ({{ $offer->discount_value }}% Off)
              @else
                ({{ $offer->discount_value }} {{ config('app.currency', 'AED') }} Off)
              @endif
            </option>
          @endforeach
        </select>
      </div>

      <!-- Linked Offers -->
      @if ($variant->offers->isNotEmpty())
        <div class="mt-3">
          <label class="font-weight-semibold text-muted mb-2">Linked Offers</label>

          @foreach ($variant->offers as $offer)
            <div class="card shadow-sm border mb-3">
              <div class="card-header">
                <div class="card-title">
                  <span
                    class="badge badge-pill
                    {{ $offer->discount_type === 'percent' ? 'badge-success' : 'badge-info' }}">
                    {{ $offer->discount_type === 'percent' ? $offer->discount_value . '% Off' : $offer->discount_value . ' ' . config('app.currency', 'AED') . ' Off' }}
                  </span>
                </div>
                <div class="card-tools">
                  <button type="submit" class="btn btn-sm btn-outline-danger btn-delete"
                    data-url="{{ route('admin.catalog.product.variant.offers.destroy', [$variant->product, $variant, $offer]) }}"
                    title="Remove Offer">
                    <i class="fas fa-trash-alt"></i>
                  </button>

                </div>
              </div>

              <div class="card-body py-2">
                <p class="mb-1"><strong>{{ $offer->translation->title }}</strong></p>

                @if ($offer->starts_at || $offer->ends_at)
                  <p class="mb-0 text-muted small">
                    Valid From:
                    @if ($offer->starts_at)
                      <span>{{ $offer->starts_at->format('d M Y h:i A') }}</span>
                    @endif
                  </p>
                  <p class="mb-0 text-muted small">
                    Valid To:
                    @if ($offer->ends_at)
                      <span> to {{ $offer->ends_at->format('d M Y h:i A') }}</span>
                    @endif
                  </p>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      @endif

    </div>
  </div>

  <!-- Footer -->
  <div class="p-3 border-top bg-white">
    <div class="d-flex justify-content-between">
      <button type="button" class="btn btn-outline-secondary" data-widget="control-sidebar"
        data-slide="true">Cancel</button>
      <button type="submit" class="btn btn-secondary">Save</button>
    </div>
  </div>
</form>

<script>
  $(document).ready(function() {
    $("form.ajax-form").each(function() {
      handleFormSubmission(this);
    });
  });
</script>
