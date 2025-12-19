@foreach ($variants as $variant)
  <div class="card shadow-sm mb-4 border rounded-lg">
    <div class="card-header bg-light  py-2 px-3">
      <div class= "card-title">
        <h6 class="mb-0 text-primary font-weight-bold">{{ $variant->sku }}</h6>
        <small class="text-muted">{{ config('app.currency', 'AED') }} {{ number_format($variant->price, 2) }}</small>
      </div>
      <div class="card-tools">
        <div class="dropdown pe-3">
          <a href="#" class="text-secondary" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-ellipsis-v"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-right">
            <li>
              <a class="dropdown-item" href="#" onclick="getAside()"
                data-url="{{ route('admin.catalog.product.variant.offers.index', ['product' => $product->id, 'variant' => $variant->id]) }}">
                <i class="fas fa-tags text-info mr-1"></i> Manage Offer
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="#" onclick="getAside()"
                data-url="{{ route('admin.catalog.product.variants.edit', ['product' => $product->id, 'variant' => $variant->id]) }}">
                <i class="fas fa-edit text-warning mr-1"></i> Edit
              </a>
            </li>
            <li>
              <a class="dropdown-item btn-delete" href="#"
                data-url="{{ route('admin.catalog.product.variants.destroy', ['product' => $product->id, 'variant' => $variant->id]) }}">
                <i class="fas fa-trash-alt text-danger mr-1"></i> Delete
              </a>
            </li>
          </ul>
        </div>

      </div>
    </div>

    <div class="card-body px-3 py-2">

      {{-- Stock & Shipping --}}
      <div class="mb-2 text-muted small">

        <i class="fas fa-boxes"></i>
        Stock: <strong>{{ $variant->stock }}</strong>

        @if ($variant->packagings->count())
          <div class="mt-2 p-2 border rounded bg-light">

            <div class="fw-bold mb-1">
              <i class="fas fa-box-open"></i> Packaging Details
            </div>

            <ul class="list-unstyled mb-0">
              @foreach ($variant->packagings as $packaging)
                <li class="d-flex justify-content-between">
                  <span class="text-secondary">
                    {{ $packaging->name }}
                  </span>
                  <span class="fw-semibold">
                    {{ $packaging->pivot->value }}
                  </span>
                </li>
              @endforeach
            </ul>

          </div>
        @endif

      </div>


      {{-- Attributes --}}
      <div class="mb-2 d-flex flex-wrap">
        @foreach ($variant->attributeValues as $attr)
          <span class="badge bg-primary text-white mr-2 mb-2">
            {{ $attr->attribute->name }}: {{ $attr->value }}
          </span>
        @endforeach
      </div>

      {{-- Tags --}}
      <div class="mb-2 d-flex flex-wrap">
        @foreach ($variant->tags as $tag)
          <span class="badge badge-secondary text-white mr-2 mb-2">
            <i class="fa fa-tag"></i>
            {{ $tag->name }}
          </span>
        @endforeach
      </div>

      {{-- Linked Offers --}}
      @if ($variant->offers->count())
        <div class="mt-2 border-top pt-2">
          <h6 class="text-muted mb-2"><i class="fas fa-tags"></i> Linked Offer(s)</h6>
          @foreach ($variant->offers as $offer)
            <div class="bg-light p-2 rounded mb-2">
              <div class="font-weight-bold text-dark">{{ $offer->translation->title }}</div>
              <div class="text-muted small">
                @if ($offer->discount_type === 'percent')
                  {{ $offer->discount_value }}% Off
                @else
                  {{ $offer->discount_value }} {{ config('app.currency', 'AED') }} Off
                @endif
              </div>
              @if ($offer->starts_at || $offer->ends_at)
                <div class="text-muted small">
                  Valid:
                  @if ($offer->starts_at)
                    <span>from {{ $offer->starts_at->format('d-M-Y  h:m A') }}</span>
                  @endif
                  @if ($offer->ends_at)
                    <span> to {{ $offer->ends_at->format('d-M-Y  h:m A') }}</span>
                  @endif
                </div>
              @endif
            </div>
          @endforeach
        </div>
      @endif

      {{-- Attachments --}}
      @if ($variant->attachments->count())
        <div class="mt-3 d-flex flex-wrap" style="gap: 8px;">
          @foreach ($variant->attachments as $attachment)
            <img src="{{ $attachment->url }}" class="img-thumbnail img-md"
              style="width: 80px; height: 80px; object-fit: cover;">
          @endforeach
        </div>
      @endif

    </div>
  </div>
@endforeach
