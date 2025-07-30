    <section class="gift-set-section py-5">
      <div class="container">
        <div class="row g-0 gift-set-grid">
          <!-- LEFT: Full-height box -->
          <div class="col-lg-6 animate-on-scroll" data-animate="fade-right">
            @foreach (collect($products)->take(1) as $product)
              <div class="gift-box h-100 gift-left position-relative  p-4">
                <div class="z-2 px-3">
                  <h2 class="mb-3">Gift Set</h2>

                </div>
                <div class="z-2 d-flex justify-content-between px-3">
                  <p class="fs-5 fw-semibold mt-3">
                    {{ $product->name }}
                  </p>
                  <p class="fs-2 fw-normal mt-3 z-2 align-self-end"> {{ active_currency() }} {{ $product->price }}
                  </p>

                </div>
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="gift-img img-left-big">
              </div>
            @endforeach
          </div>

          <!-- RIGHT: Two stacked half-height boxes -->
          <div class="col-lg-6 d-flex flex-column animate-on-scroll" data-animate="fade-left">
            @foreach (collect($products)->skip(1)->take(2) as $product)
              @if ($loop->first)
                <div
                  class="gift-box gift-right-half bg-lightblue flex-fill position-relative text-white p-4 d-flex flex-column justify-content-between">
                  <div class="z-2 px-4">
                    <p class="fs-5 fw-bold my-3">{{ $product->name }}</p>
                    <p class="fs-2 fw-normal">{{ active_currency() }} {{ $product->price }} </p>
                  </div>
                  <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="gift-img img-right-small">
                </div>
              @else
                <!-- Bottom box -->
                <div
                  class="gift-box gift-right-half bg-tan flex-fill position-relative text-white p-4 d-flex flex-column justify-content-between">
                  <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="gift-img img-left-small">
                  <div class="z-2 text-start px-4">
                    <p class="fs-5 fw-bold my-3">{{ $product->name }}</p>
                    <p class="fs-2 fw-normal">{{ active_currency() }} {{ $product->price }} </p>
                  </div>
                </div>
              @endif
            @endforeach


          </div>
        </div>
      </div>
    </section>
