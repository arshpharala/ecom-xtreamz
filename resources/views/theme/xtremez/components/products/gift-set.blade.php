    <section class="gift-set-section py-5">
      <div class="container">
        <div class="row gift-set-boxes">
          <!-- LEFT: Full-height box -->
          <div class="col-lg-6 animate-on-scroll" data-animate="fade-right">
            @foreach (collect($products)->take(1) as $product)
              <div class="gift-box gift-left">
                <div class="gift-box-item">
                  <div class="gift-content-box">
                    <p class="fs-5 fw-bold my-3">{{ $product->name }}</p>
                    <div class="price-bar d-none d-md-flex d-lg-none">
                      <div class="price">
                        {!! price_format(active_currency(true)->code, $product->price) !!}
                      </div>
                      <a class="btn-circle" href="{{ $product->link }}">
                        <i class="bi bi-arrow-right fw-bold"></i>
                      </a>
                    </div>
                  </div>
                  <div class="gift-img-box">
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="gift-img">
                  </div>
                  <div class="price-bar align-self-center d-flex d-md-none d-lg-flex">
                    <div class="price">
                      {!! price_format(active_currency(true)->code, $product->price) !!}
                    </div>
                    <a class="btn-circle" href="{{ $product->link }}">
                      <i class="bi bi-arrow-right fw-bold"></i>
                    </a>
                  </div>
                </div>
              </div>
            @endforeach
          </div>

          <!-- RIGHT: Two stacked half-height boxes -->
          <div class="col-lg-6 d-flex flex-column gap-4 animate-on-scroll" data-animate="fade-left">
            @foreach (collect($products)->skip(1)->take(2) as $product)
              <div class="gift-box gift-right-half">
                <div class="gift-box-item">
                  <div class="gift-content-box">
                    <p class="fs-5 fw-bold my-3">{{ $product->name }}</p>
                    <div class="price-bar d-none d-md-flex d-lg-flex">
                      <div class="price">
                        {!! price_format(active_currency(true)->code, $product->price) !!}
                      </div>
                      <a class="btn-circle" href="{{ $product->link }}">
                        <i class="bi bi-arrow-right fw-bold"></i>
                      </a>
                    </div>

                  </div>
                  <div class="gift-img-box">
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="gift-img">
                  </div>
                </div>
                <div class="price-bar align-self-center d-flex d-md-none d-lg-none">
                  <div class="price">
                    {!! price_format(active_currency(true)->code, $product->price) !!}
                  </div>
                  <a class="btn-circle" href="{{ $product->link }}">
                    <i class="bi bi-arrow-right fw-bold"></i>
                  </a>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </section>
