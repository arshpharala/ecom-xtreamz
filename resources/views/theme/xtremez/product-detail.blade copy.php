@extends('theme.xtremez.layouts.app')

@section('content')
<section class="product-detail py-5">
  <div class="container">
    <!-- Mobile Title -->
    <h2 class="fs-2 mb-4 d-lg-none">{{ $product->translations()->first()?->name }}</h2>

    <div class="row gx-5">
      <!-- Left: Image Gallery -->
      <div class="col-12 col-lg-6">
        <div class="main-image position-relative bg-white">
          <img id="zoomImage" src="{{ asset(optional($variant->attachments->first())->path ?? 'theme/assets/images/no-image.png') }}"
               alt="Product Image"
               class="img-fluid w-75 mx-auto d-block">
        </div>

        <div class="thumbnail-carousel d-flex align-items-center py-2">
          <button class="thumb-nav btn btn-link p-0 me-2 no-animate" id="thumbPrev">
            <i class="bi bi-chevron-left fs-2 text-black"></i>
          </button>

          <div class="thumb-wrapper d-flex overflow-auto">
            @foreach ($variant->attachments as $image)
              <img src="{{ asset($image->path) }}"
                   data-large="{{ asset($image->path) }}"
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
        <h2 class="fs-2 mb-3 d-none d-lg-block">{{ $product->translations()->first()?->name }}</h2>

        <div class="product-description bg-white p-5 mb-4">
          <strong>Product Description:</strong>
          <p>{{ $product->translations()->first()?->description }}</p>
        </div>

        <div class="price fs-3 py-3">{{ $variant->price }} {{ active_currency() }}</div>

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

          <!-- Attribute Swatches -->
          @foreach ($groupedAttributes as $attrName => $values)
          <div class="d-flex align-items-center gap-3">
            <div>
              <label class="form-label mb-0">{{ $attrName }}</label>
              <div class="color-swatches d-flex gap-2 py-2">
                @foreach ($values->unique('id') as $val)
                  <div class="color-swatch {{ in_array($val->id, $variant->attributeValues->pluck('id')->toArray()) ? 'active' : '' }}"
                       data-value-id="{{ $val->id }}"
                       style="background: {{ $val->value }}"
                       title="{{ $val->value }}">
                  </div>
                @endforeach
              </div>
            </div>
          </div>
          @endforeach
        </div>

        <div class="d-flex gap-3 py-3">
          <button class="btn btn-cart flex-fill">Buy Now</button>
          <button class="btn btn-buy flex-fill">Add to Cart</button>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Specifications -->
<section class="product-specifications pb-5">
  <div class="container">
    <div class="spec-box bg-white p-5 shadow-sm">
      <h3 class="section-title fw-bold fs-5">Specifications of Products</h3>
      <table class="table specs-table">
        <thead>
          <tr><th colspan="2" class="pt-4 pb-2 text-dark fw-bold">Product</th></tr>
        </thead>
        <tbody>
          <tr><td>Brand</td><td>{{ $product->brand?->name ?? '-' }}</td></tr>
        </tbody>
        <thead>
          <tr><th colspan="2" class="pt-5 pb-2 text-dark fw-bold">Packing</th></tr>
        </thead>
        <tbody>
          <tr><td>Qty per Carton</td><td>{{ $variant->shipping?->quantity_per_carton ?? '-' }}</td></tr>
          <tr><td>Carton Gross Weight</td><td>{{ $variant->shipping?->weight }} kgs</td></tr>
          <tr><td>Carton Dimensions</td><td>{{ $variant->shipping?->length }} x {{ $variant->shipping?->width }} x {{ $variant->shipping?->height }} cm</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- Related Products -->
<section class="product-section py-5">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="section-title fs-1" data-text="Related Products">Related Products</h2>
      <div class="section-nav">
        <button id="productCustomPrev" class="btn btn-link p-0 me-2"><i class="bi bi-chevron-left"></i></button>
        <button id="productCustomNext" class="btn btn-link p-0"><i class="bi bi-chevron-right"></i></button>
      </div>
    </div>

    <div id="product-carousel" class="owl-carousel owl-theme">
      @foreach ($relatedProducts as $related)
        <div class="item">
          <div class="card">
            <img src="{{ asset(optional($related->attachments->first())->path ?? 'theme/assets/images/no-image.png') }}" class="card-img-top" alt="{{ $related->product->translations()->first()?->name }}">
            <div class="card-body text-center">
              <h5 class="card-title">{{ $related->product->translations()->first()?->name }}</h5>
              <p class="card-text">{{ active_currency() }} {{ $related->price }}</p>
              <a href="{{ route('products.show', ['slug' => $related->product->slug, 'variant' => $related->id]) }}" class="btn btn-outline-primary btn-sm">View</a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endsection
