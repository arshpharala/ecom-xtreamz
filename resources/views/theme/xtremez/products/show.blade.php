@extends('theme.xtremez.layouts.app')
@push('head')
  <meta name="variant-id" content="{{ $productVariant->variant_id }}">
  <meta name="product-id" content="{{ $productVariant->product_id }}">
  <meta name="currency" content="{{ active_currency() }}">


  <!-- Owl Carousel CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
@endpush

@section('breadcrumb')
  <section class="breadcrumb-bar py-2">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 py-2">
          <li class="breadcrumb-item">
            <a href="{{ route('products') }}" class="text-white" title="All Products">
              <!-- <i class="bi bi-house"></i> -->
              All Products
            </a>
          </li>
          <li class="breadcrumb-item active text-white" aria-current="page" title="{{ $productVariant->name }}">
            {{ $productVariant->name }}
          </li>
        </ol>
      </nav>
    </div>
  </section>
@endsection

@section('content')
  <section class="product-detail py-5" data-variant-id="{{ $productVariant->id }}"
    data-price="{{ $productVariant->price }}" data-qty="{{ $productVariant->cart_item['qty'] ?? 1 }}">
    <div class="container">

      <!-- Mobile Title -->
      <h2 class="fs-2 mb-4 d-lg-none">
        {{ $productVariant->name }}
      </h2>

      <div class="row gx-5">

        <!-- Left: Image Gallery -->
        <div class="col-12 col-lg-6">
          <div class="main-image position-relative bg-white">
            <!-- Stock Badge -->
            <div class="stock-badge {{ $productVariant->stock == 0 ? 'out-of-stock' : '' }}">
              <span class="stock-text">
                @if ($productVariant->stock > 0)
                  In Stock: {{ $productVariant->stock }}
                @else
                  Out of Stock
                @endif
              </span>
            </div>

            <img id="zoomImage" src="{{ get_attachment_url($productVariant->file_path) }}" alt="Product Image"
              class="img-fluid w-75 mx-auto d-block">
          </div>

          <div class="thumbnail-carousel d-flex align-items-center py-2">
            <button class="thumb-nav btn btn-link p-0 me-2 no-animate" id="thumbPrev">
              <i class="bi bi-chevron-left fs-2 text-black"></i>
            </button>
            <div class="thumb-wrapper d-flex overflow-auto">
              @foreach ($productVariant->attachments as $image)
                <img src="{{ get_attachment_url($image->file_path) }}" alt="img-thumbnail"
                  data-large="{{ get_attachment_url($image->file_path) }}"
                  class="thumb-item {{ $loop->first ? 'active' : '' }} me-2" />
              @endforeach
            </div>
            <button class="thumb-nav btn btn-link p-0 ms-2 no-animate" id="thumbNext">
              <i class="bi bi-chevron-right fs-2 text-black"></i>
            </button>
          </div>
        </div>

        <!-- Right: Product Details -->
        <div class="col-12 col-lg-6">

          <!-- Desktop Title -->
          <h2 class="fs-2 mb-3 d-none d-lg-block">
            {{ $productVariant->name }}
          </h2>

          <div class="product-description bg-white p-5 mb-4">
            <strong>Product Description:</strong>
            <div>
              {!! $productVariant->description !!}
            </div>
          </div>
          @php
            $hasOffer = isset($productVariant->offer_data['has_offer']) && $productVariant->offer_data['has_offer'];
            $discountedPrice = $productVariant->offer_data['discounted_price'] ?? null;
          @endphp

          <div class="price fs-3 py-3" id="priceDisplay">
            @if ($hasOffer)
              <span class="text-danger fw-bold">
                {!! $productVariant->offer_data['discounted_price_with_currency'] !!}
                {{-- {{ active_currency() }} {{ number_format($discountedPrice, 2) }} --}}
              </span>
              <span class="text-muted text-decoration-line-through ms-2">
                {{-- {{ active_currency() }} {{ number_format($productVariant->price, 2) }} --}}

                {!! $productVariant->price_with_currency !!}
              </span>
              <span class="badge bg-secondary ms-2">{{ $productVariant->offer_data['label'] }}</span>
            @else
              <span>
                {!! $productVariant->price_with_currency !!}
                {{-- {{ price_format(active_currency(), number_format($productVariant->price, 2)) }} --}}
                {{-- {{ active_currency() }} {{ number_format($productVariant->price, 2) }} --}}
              </span>
            @endif
          </div>

          <div class="d-flex align-items-center justify-content-start gap-4 py-3 flex-wrap product-options">
            @php
              $hasSizeAttribute = collect($attributes)->contains(fn($attr) => Str::lower($attr['name']) === 'size');
            @endphp

            @unless ($hasSizeAttribute)
              <!-- Quantity -->
              <div class="d-flex align-items-center gap-2">
                <div>
                  <label class="form-label mb-0">Quantity</label>
                  <div class="qty-wrapper d-flex align-items-center">
                    {{-- <i class="bi bi-dash-circle qty-btn minus" id="qtyMinus"></i> --}}
                    <i class="bi bi-dash-circle qty-btn minus"></i>
                    <input type="text" id="qtyInput" class="qty-input py-1" value="1" />
                    <i class="bi bi-plus-circle qty-btn plus"></i>
                    {{-- <i class="bi bi-plus-circle qty-btn plus" id="qtyPlus"></i> --}}
                  </div>
                </div>
              </div>

              <!-- Divider -->
              <div class="vr-line d-none d-md-block"></div>
            @endunless

            <!-- Dynamic Attributes -->
            <!-- Dynamic Attributes -->
            @foreach ($attributes as $slug => $attr)
              @php
                $isSizeAttribute = Str::lower($attr['name']) === 'size';
              @endphp

              @if ($isSizeAttribute)
                {{-- SIZE ATTRIBUTE: Show inline quantity inputs --}}
                <div class="size-quantity-selector w-100" data-attr="{{ $slug }}">
                  <label class="form-label mb-2 fw-bold">{{ $attr['name'] }} & Quantity</label>
                  <div class="size-qty-grid">
                    @foreach ($attr['values'] as $sizeValue)
                      @php
                        // Find the variant for this size
                        $sizeVariant = $product->variants->first(function ($v) use ($sizeValue) {
                            return $v->attributeValues->contains(function ($av) use ($sizeValue) {
                                return Str::lower($av->attribute->name) === 'size' && $av->value === $sizeValue;
                            });
                        });
                        $variantStock = $sizeVariant->stock ?? 0;
                        $isActive = isset($selected[$slug]) && $selected[$slug] === $sizeValue;
                      @endphp
                      <div class="size-qty-item {{ $variantStock <= 0 ? 'out-of-stock' : '' }}">
                        <label class="size-label">{{ $sizeValue }}</label>
                        <input type="number" class="size-qty-input" data-size="{{ $sizeValue }}"
                          data-variant-id="{{ $sizeVariant->id ?? '' }}" data-stock="{{ $variantStock }}" min="0"
                          max="{{ $variantStock }}" value="0" placeholder="0"
                          {{ $variantStock <= 0 ? 'disabled' : '' }} />
                        <small
                          class="stock-info">{{ $variantStock > 0 ? "Stock: $variantStock" : 'Out of Stock' }}</small>
                      </div>
                    @endforeach
                  </div>
                </div>
              @else
                {{-- OTHER ATTRIBUTES: Show improved selector --}}
                <div class="attribute-group w-100" data-attr="{{ $slug }}">
                  <label class="form-label mb-2 fw-bold">{{ $attr['name'] }}</label>
                  <div class="d-flex flex-wrap gap-3 attribute-options">
                    @foreach ($attr['values'] as $val)
                      @php
                        $isColor = Str::lower($attr['name']) === 'color';
                        $isActive = isset($selected[$slug]) && $selected[$slug] === $val;

                        // For colors, find a representative variant image
                        $variantThumb = null;
                        if ($isColor) {
                            $colorVariant = $product->variants->first(function ($v) use ($val) {
                                return $v->attributeValues->contains(function ($av) use ($val) {
                                    return Str::lower($av->attribute->name) === 'color' && $av->value === $val;
                                });
                            });

                            $variantThumb = $colorVariant
                                ? get_attachment_url($colorVariant->attachments->first()->file_path)
                                : null;
                        }
                      @endphp
                      <div
                        class="variant-option {{ $isColor ? 'color-variant-option' : 'option-box' }} {{ $isActive ? 'active' : '' }}"
                        data-value="{{ $val }}" data-attr="{{ $slug }}">
                        @if ($isColor)
                          @if ($variantThumb)
                            <img src="{{ $variantThumb }}" alt="{{ $val }}" class="color-thumb">
                          @else
                            <div class="color-placeholder" style="background:{{ $val }};"></div>
                          @endif
                          <span class="option-text">{{ $val }}</span>
                        @else
                          <span class="option-text">{{ $val }}</span>
                        @endif
                      </div>
                    @endforeach
                  </div>
                </div>
              @endif
            @endforeach





            <!-- Color Family -->
            {{-- <div class="d-flex align-items-center gap-3">
              <div>
                <label class="form-label mb-0">Color Family</label>
                <div class="color-swatches d-flex gap-2 py-2">
                  <div class="color-swatch active" style="background: #5d6266;"></div>
                  <div class="color-swatch" style="background: #ffffff;"></div>
                  <div class="color-swatch" style="background: #d5b3a2;"></div>
                </div>
              </div>
            </div> --}}
          </div>

          <div class="d-flex gap-3 py-3">
            <button
              class="btn btn-cart flex-fill buy-now-btn {{ !empty($productVariant->cart_item['qty']) ? 'in-cart' : '' }}"
              data-variant-id="{{ $productVariant->id }}" data-qty-selector="#qtyInput">Buy Now</button>
            <button class="btn btn-buy flex-fill add-to-cart-btn" data-variant-id="{{ $productVariant->id }}"
              data-qty-selector="#qtyInput">
              <span class="add-to-cart">
                Add to Cart
              </span>
              <span class="added-to-cart" style="display:none">
                Added to Cart
              </span>
            </button>
          </div>

        </div>
      </div>
    </div>
  </section>

  <section class="product-specifications py-5">
    <div class="container">
      <div class="spec-box bg-white p-5 shadow-sm">
        <h3 class="section-title fw-bold fs-5">
          Specifications of Products
        </h3>

        <table class="table specs-table">
          <thead>
            <tr>
              <th colspan="2" class="pt-4 pb-2 text-dark fw-bold">Product</th>
            </tr>
          </thead>

          <tbody>
            <tr>
              <td>Brand</td>
              <td id="product-brand">{{ $productVariant->brand_name ?? 'NA' }}</td>
            </tr>
          </tbody>

          <thead>
            <tr>
              <th colspan="2" class="pt-5 pb-2 text-dark fw-bold">Packing</th>
            </tr>
          </thead>

          <tbody>
            @php
              $packagingMap = $productVariant->packagings->mapWithKeys(
                  fn($p) => [
                      strtolower($p->name) => $p->pivot->value,
                  ],
              );
            @endphp

            <tr>
              <td>Qty per Carton</td>
              <td id="product-qty-per-carton">
                {{ $packagingMap['qty per carton'] ?? 'NA' }}
              </td>
            </tr>

            <tr>
              <td>Carton Gross Weight</td>
              <td id="product-carton-gross-weight">
                {{ $packagingMap['carton gross weight (kgs / carton)'] ?? 'NA' }}
              </td>
            </tr>

            <tr>
              <td>Carton Dimensions (cm)</td>
              <td id="product-carton-dimenssions">
                {{ $packagingMap['carton dimensions (cm)'] ?? 'NA' }}
              </td>
            </tr>

            <tr>
              <td>HS / Commodity Code</td>
              <td id="product-hs-code">
                {{ $packagingMap['hs / commodity code'] ?? 'NA' }}
              </td>
            </tr>
          </tbody>
        </table>

      </div>
    </div>
  </section>

  <section class="product-section py-5">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title fs-1" data-text="Related Products">Related
          Products</h2>
        <div class="section-nav">
          <button id="productCustomPrev" class="btn btn-link p-0 me-2">
            <i class="bi bi-chevron-left"></i>
          </button>
          <button id="productCustomNext" class="btn btn-link p-0">
            <i class="bi bi-chevron-right"></i>
          </button>
        </div>
      </div>

      <div id="product-carousel" class="owl-carousel owl-theme category-item"
        data-category-id="{{ $productVariant->category_id }}"></div>

    </div>
  </section>
@endsection


@push('scripts')
  <script>
    window.allVariants = @json($allVariants);
    window.selectedAttributes = @json($selected);

    window.variant = @json($productVariant);

    window.currentVariantId = "{{ $productVariant->id }}";
    window.basePrice = {{ $productVariant->price }};

    window.ajaxVarianrURL = "{{ route('ajax.variants.resolve') }}";

    window.ajaxProductURL = "{{ route('ajax.get-products', ['exclude_ids' => [$productVariant->id]]) }}";
    window.activeCategoryId = "{{ $productVariant->category_id }}";
  </script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

  <script src="{{ asset('theme/xtremez/assets/js/product-carousel.js') }}"></script>
  <script src="{{ asset('theme/xtremez/assets/js/size-quantity-handler.js') }}"></script>
  <script src="{{ asset('theme/xtremez/assets/js/product-detail.js') }}"></script>
@endpush
