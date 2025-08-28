    <section class="gift-set-section py-5">
      <div class="container">
        <div class="row gift-set-grid">
          <!-- LEFT: Full-height box -->
          <div class="col-lg-6 animate-on-scroll" data-animate="fade-right">
            @foreach (collect($products)->take(1) as $product)
              <div class="gift-box h-100 gift-left position-relative  p-4">
                <div class="z-2 px-4 d-flex flex-column h-100 justify-content-between">
                  <p class="fs-5 fw-bold my-3">{{ $product->name }}</p>
                  <div class="price-bar align-self-center">
                    <div class="price">
                      {!! price_format(active_currency(true)->code, $product->price) !!}
                    </div>
                    <div class="btn-circle">
                      <i class="bi bi-arrow-right fw-bold"></i>
                    </div>
                  </div>

                </div>
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="gift-img img-left-big">
              </div>
            @endforeach
          </div>

          <!-- RIGHT: Two stacked half-height boxes -->
          <div class="col-lg-6 d-flex flex-column gap-4 animate-on-scroll" data-animate="fade-left">
            @foreach (collect($products)->skip(1)->take(2) as $product)
              {{-- @if ($loop->first) --}}
              <div
                class="gift-box gift-right-half bg-lightblue flex-fill position-relative text-white p-4 d-flex flex-column justify-content-between">
                <div class="z-2 px-4 d-flex flex-column h-100 justify-content-between">
                  <p class="fs-5 fw-bold my-3">{{ $product->name }}</p>
                  <div class="price-bar">
                    <div class="price">
                      {!! price_format(active_currency(true)->code, $product->price) !!}
                    </div>
                    <div class="btn-circle">
                      <i class="bi bi-arrow-right fw-bold"></i>
                    </div>
                  </div>
                </div>
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="gift-img img-right-small">
              </div>
              {{-- @else
                <!-- Bottom box -->
                <div
                  class="gift-box gift-right-half bg-tan flex-fill position-relative text-white p-4 d-flex flex-column justify-content-between">
                  <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="gift-img img-left-small">
                  <div class="z-2 text-start px-4 d-flex flex-column h-100 justify-content-between">
                    <div>
                        <p class="fs-5 fw-bold my-3">{{ $product->name }}</p>
                    </div>
                    <div class="price-bar">
                      <div class="price">
                        {!! price_format(active_currency(true)->code, $product->price) !!}
                      </div>
                      <div class="btn-circle">
                        <i class="bi bi-arrow-right fw-bold"></i>
                      </div>
                    </div>
                  </div>
                </div>
              @endif --}}
            @endforeach


          </div>
        </div>
      </div>
    </section>
