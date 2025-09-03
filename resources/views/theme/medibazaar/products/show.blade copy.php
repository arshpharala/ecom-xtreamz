@extends('theme.medibazaar.layouts.app')

@push('head')
  <meta name="variant-id" content="{{ $productVariant->id }}">
  <meta name="product-id" content="{{ $productVariant->product_id }}">
  <meta name="currency" content="{{ active_currency() }}">
@endpush

@section('content')
<!-- shop-banner-area start -->
<section class="shop-banner-area pt-100 pb-70 product-detail"
    data-variant-id="{{ $productVariant->id }}"
    data-price="{{ $productVariant->price }}"
    data-qty="{{ $productVariant->cart_item['qty'] ?? 1 }}">

  <div class="container">
    <div class="row">
      <!-- Left: Image Gallery -->
      <div class="col-xl-6 col-lg-6">
        <div class="shop-thumb-tab">
          <ul class="nav" id="thumbNav" role="tablist">
            @foreach($productVariant->attachments as $image)
              <li class="nav-item">
                <a class="nav-link {{ $loop->first ? 'active' : '' }}"
                   data-toggle="tab"
                   href="#tab-{{ $loop->index }}">
                  <img src="{{ asset('storage/' . $image->file_path) }}" alt="">
                </a>
              </li>
            @endforeach
          </ul>
        </div>

        <div class="product-details-img mb-30">
          <div class="tab-content" id="thumbContent">
            @foreach($productVariant->attachments as $image)
              <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                   id="tab-{{ $loop->index }}">
                <div class="product-large-img">
                  <img id="zoomImage"
                       src="{{ asset('storage/' . $image->file_path) }}"
                       alt="{{ $productVariant->name }}">
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>

      <!-- Right: Product Info -->
      <div class="col-xl-6 col-lg-6">
        <div class="product-details-wrapper mb-30">
          <div class="product-text">
            <h5>{{ $productVariant->brand_name ?? 'Brand' }}</h5>
            <h4>{{ $productVariant->name }}</h4>

            <!-- Price -->
            <div id="priceDisplay" class="fs-4 py-2">
              {!! $productVariant->price_with_currency !!}
            </div>
          </div>

          <div class="product-variant">
            <!-- Description -->
            <div class="product-desc">
              {!! $productVariant->description !!}
            </div>

            <!-- Dynamic Attributes -->
            @foreach ($attributes as $slug => $attr)
              <div class="product-info-list" data-attr="{{ $slug }}">
                <span>{{ $attr['name'] }}</span>
                @foreach ($attr['values'] as $val)
                  @php
                    $isActive = isset($selected[$slug]) && $selected[$slug] === $val;
                    $isColor = Str::lower($attr['name']) === 'color';
                  @endphp
                  <a href="javascript:void(0)"
                     class="variant-option {{ $isColor ? 'color-swatch' : 'option-box' }} {{ $isActive ? 'active' : '' }}"
                     data-attr="{{ $slug }}" data-value="{{ $val }}"
                     @if($isColor) style="background: {{ $val }}" @endif>
                     @unless($isColor) {{ $val }} @endunless
                  </a>
                @endforeach
              </div>
            @endforeach

            <!-- Stock -->
            <span class="stock">{{ $productVariant->stock }} In Stock</span>

            <!-- Qty + Cart -->
            <div class="product-action-details">
              <div class="d-flex gap-3 py-3">
                <div class="plus-minus">
                  <div class="cart-plus-minus">
                    <input type="text" id="qtyInput" value="{{ $productVariant->cart_item['qty'] ?? 1 }}">
                    <div class="dec qtybutton">-</div>
                    <div class="inc qtybutton">+</div>
                  </div>
                </div>
                <div>
                  <button class="c-btn red-btn add-to-cart-btn"
                          data-variant-id="{{ $productVariant->id }}"
                          data-qty-selector="#qtyInput">
                    Add to Cart <i class="far fa-plus"></i>
                  </button>
                </div>
              </div>
            </div>

            <!-- Extra Info -->
            <div class="product-02-list">
              <ul>
                <li><span>SKU :</span> <span>{{ $productVariant->sku ?? 'N/A' }}</span></li>
                <li><span>Categories :</span> <span>{{ $productVariant->category->name ?? 'N/A' }}</span></li>
              </ul>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- shop-banner-area end -->

<!-- product-desc-area start -->
<section class="product-desc-area pb-60">
  <div class="container">
    <ul class="nav text-center pb-30 mb-50" role="tablist">
      <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#id-desc">Description</a></li>
      <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#id-specs">Specifications</a></li>
    </ul>

    <div class="tab-content">
      <div class="tab-pane fade show active" id="id-desc">
        {!! $productVariant->description !!}
      </div>
      <div class="tab-pane fade" id="id-specs">
        <table class="table">
          <tr><th>Brand</th><td>{{ $productVariant->brand_name ?? 'N/A' }}</td></tr>
          <tr><th>Dimensions</th><td>{{ $productVariant->shipping?->length }} x {{ $productVariant->shipping?->width }} x {{ $productVariant->shipping?->height }} cm</td></tr>
          <tr><th>Weight</th><td>{{ $productVariant->shipping?->weight }} kgs</td></tr>
        </table>
      </div>
    </div>
  </div>
</section>
<!-- product-desc-area end -->

<!-- Related Products -->
<section class="product-area pb-100">
  <div class="container">
    <h2 class="text-center mb-5">Related Products</h2>
    <div id="product-carousel" class="owl-carousel owl-theme"
         data-category-id="{{ $productVariant->category_id }}">
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
  window.selectedAttributes = @json($selected);
  window.variant = @json($productVariant);
  window.ajaxVarianrURL = "{{ route('ajax.variants.resolve') }}";
  window.ajaxProductURL = "{{ route('ajax.get-products') }}";
</script>
<script src="{{ asset('theme/xtremez/assets/js/product-detail.js') }}"></script>
<script src="{{ asset('theme/xtremez/assets/js/product-carousel.js') }}"></script>
@endpush
