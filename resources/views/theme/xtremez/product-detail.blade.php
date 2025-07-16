@extends('theme.xtremez.layouts.app')
@push('head')
  <meta name="variant-id" content="{{ $productVarient->variant_id }}">
  <meta name="product-id" content="{{ $productVarient->product_id }}">
  <meta name="currency" content="{{ active_currency() }}">
  <script>
    window.variantMap = @json($variantMap);
  </script>


  <!-- Owl Carousel CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />

  <style>
    .option-box {
      padding: 6px 12px;
      border: 1px solid #ccc;
      border-radius: 4px;
      cursor: pointer;
      min-width: 48px;
      text-align: center;
      transition: all 0.2s ease-in-out;
      background-color: #fff;
    }

    .option-box:hover {
      border-color: #333;
    }

    .option-box.active {
      border-color: #000;
      font-weight: bold;
    }

    .option-text {
      display: inline-block;
      font-size: 14px;
      color: #333;
    }

    .color-swatch {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      border: 2px solid #ccc;
      cursor: pointer;
    }

    .color-swatch.active {
      border: 2px solid #000;
    }
  </style>
@endpush

@section('breadcrumb')
  <section class="breadcrumb-bar py-2">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 py-2">
          <li class="breadcrumb-item">
            <a href="#" class="text-white" title="All Products">
              <!-- <i class="bi bi-house"></i> -->
              All Products
            </a>
          </li>
          <li class="breadcrumb-item active text-white" aria-current="page" title="{{ $productVarient->name }}">
            {{ $productVarient->name }}
          </li>
        </ol>
      </nav>
    </div>
  </section>
@endsection

@section('content')
  <section class="product-detail py-5">
    <div class="container">

      <!-- Mobile Title -->
      <h2 class="fs-2 mb-4 d-lg-none">
        {{ $productVarient->name }}
      </h2>

      <div class="row gx-5">

        <!-- Left: Image Gallery -->
        <div class="col-12 col-lg-6">
          <div class="main-image position-relative bg-white">
            <img id="zoomImage" src="{{ asset('storage/' . $productVarient->file_path) }}" alt="Product Image"
              class="img-fluid w-75 mx-auto d-block">
          </div>

          <div class="thumbnail-carousel d-flex align-items-center py-2">
            <button class="thumb-nav btn btn-link p-0 me-2 no-animate" id="thumbPrev">
              <i class="bi bi-chevron-left fs-2 text-black"></i>
            </button>
            <div class="thumb-wrapper d-flex overflow-auto">
              @foreach ($productVarient->attachments as $image)
                <img src="{{ asset('storage/' . $image->file_path) }}"
                  data-large="{{ asset('storage/' . $image->file_path) }}"
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
            {{ $productVarient->name }}
          </h2>

          <div class="product-description bg-white p-5 mb-4">
            <strong>Product Description:</strong>
            <p>
              {!! $productVarient->description !!}
            </p>
          </div>

          <div class="price fs-3 py-3">{{ active_currency() }} {{ $productVarient->price }}</div>

          <div class="d-flex align-items-center justify-content-start gap-4 py-3 flex-wrap product-options">
            <!-- Quantity -->
            <div class="d-flex align-items-center gap-2">
              <div>
                <label class="form-label mb-0">Quantity</label>
                <div class="qty-wrapper d-flex align-items-center">
                  <i class="bi bi-dash-circle qty-btn" id="qtyMinus"></i>
                  <input type="text" id="qtyInput" class="qty-input py-1" value="1" />
                  <i class="bi bi-plus-circle qty-btn" id="qtyPlus"></i>
                </div>
              </div>
            </div>

            <!-- Divider -->
            <div class="vr-line d-none d-md-block"></div>

            @foreach ($attributes as $attribute)
              @php
                $isColor = Str::lower($attribute['name']) === 'color';
                $attrSlug = Str::slug($attribute['name']);
              @endphp

              <div class="d-flex align-items-center gap-3 flex-wrap w-100 attribute-wrapper">
                <div class="w-100" data-attribute="{{ $attrSlug }}">
                  <label class="form-label mb-1">{{ $attribute['name'] }}</label>

                  <div class="attribute-options d-flex gap-2 py-2 flex-wrap">
                    @foreach ($attribute['values'] as $val)
                      @php $bg = $isColor ? "style=background:{$val}" : ''; @endphp

                      <div class="variant-option border {{ $isColor ? 'color-swatch' : 'option-box' }}"
                        data-attribute="{{ $attrSlug }}" data-value="{{ $val }}" {!! $bg !!}>
                        @unless ($isColor)
                          <span class="option-text">{{ $val }}</span>
                        @endunless
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
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
            </div>
          </div> --}}

            <div class="d-flex gap-3 py-3">
              <button class="btn btn-cart flex-fill">Buy Now</button>
              <button class="btn btn-buy flex-fill">Add to Cart</button>
            </div>

          </div>
        </div>
      </div>
  </section>

  <section class="product-specifications pb-5">
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
              <td class="">Brand</td>
              <td>{{ $productVarient->brand_name ?? 'NA' }}</td>
            </tr>
          </tbody>
          <thead>
            <tr>
              <th colspan="2" class="pt-5 pb-2 text-dark fw-bold">Packing</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="">Qty per Carton</td>
              <td>{{ $productVarient->shipping?->qty_per_carton ?? 'NA' }} pcs</td>
            </tr>
            <tr>
              <td class="">Carton Gross Weight</td>
              <td>
                {{ $productVarient->shipping?->weight }}
                kgs
              </td>
            </tr>
            <tr>
              <td class="">Carton Dimensions (cm)</td>
              <td>{{ $productVarient->shipping?->length }} x {{ $productVarient->shipping?->width }} x
                {{ $productVarient->shipping?->height }} cm</td>
            </tr>
            <tr>
              <td class="">HS / Commodity Code</td>
              <td>48201000</td>
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

      <div id="product-carousel" class="owl-carousel owl-theme"></div>

    </div>
  </section>
@endsection


@push('scripts')
  <script>
    window.productId = "{{ $productVarient->product_id }}";
  </script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

  <script src="{{ asset('theme/xtremez/assets/js/product-carousel.js') }}"></script>
  <script src="{{ asset('theme/xtremez/assets/js/product-detail.js') }}"></script>
@endpush
