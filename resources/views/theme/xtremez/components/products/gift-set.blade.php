    <section class="gift-set-section py-5">
      <div class="container">
        <div class="row gift-set-boxes">
          <!-- LEFT: Full-height box -->
          <div class="col-lg-6 animate-on-scroll" data-animate="fade-right">
            @foreach (collect($products)->take(1) as $product)
              <div class="gift-box gift-left">
                <div class="gift-box-item">
                  <div class="gift-content-box">
                    <p class="fs-5 fw-bold">{{ $product->name }}</p>
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
                      <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" stroke="#000000" stroke-width="0.24000000000000005">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                          <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M12.2929 4.29289C12.6834 3.90237 13.3166 3.90237 13.7071 4.29289L20.7071 11.2929C21.0976 11.6834 21.0976 12.3166 20.7071 12.7071L13.7071 19.7071C13.3166 20.0976 12.6834 20.0976 12.2929 19.7071C11.9024 19.3166 11.9024 18.6834 12.2929 18.2929L17.5858 13H4C3.44772 13 3 12.5523 3 12C3 11.4477 3.44772 11 4 11H17.5858L12.2929 5.70711C11.9024 5.31658 11.9024 4.68342 12.2929 4.29289Z"
                            fill="#000000"></path>
                        </g>
                      </svg>
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
                    <p class="fs-5 fw-bold">{{ $product->name }}</p>
                    <div class="price-bar">
                      <div class="price">
                        {!! price_format(active_currency(true)->code, $product->price) !!}
                      </div>
                      <a class="btn-circle" href="{{ $product->link }}">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                          xmlns="http://www.w3.org/2000/svg" stroke="#000000" stroke-width="0.24000000000000005">
                          <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                          <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                          <g id="SVGRepo_iconCarrier">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M12.2929 4.29289C12.6834 3.90237 13.3166 3.90237 13.7071 4.29289L20.7071 11.2929C21.0976 11.6834 21.0976 12.3166 20.7071 12.7071L13.7071 19.7071C13.3166 20.0976 12.6834 20.0976 12.2929 19.7071C11.9024 19.3166 11.9024 18.6834 12.2929 18.2929L17.5858 13H4C3.44772 13 3 12.5523 3 12C3 11.4477 3.44772 11 4 11H17.5858L12.2929 5.70711C11.9024 5.31658 11.9024 4.68342 12.2929 4.29289Z"
                              fill="#000000"></path>
                          </g>
                        </svg>
                      </a>
                    </div>

                  </div>
                  <div class="gift-img-box">
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="gift-img">
                  </div>
                </div>
                {{-- <div class="price-bar align-self-center d-flex d-md-none d-lg-none">
                  <div class="price">
                    {!! price_format(active_currency(true)->code, $product->price) !!}
                  </div>
                  <a class="btn-circle" href="{{ $product->link }}">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                      xmlns="http://www.w3.org/2000/svg" stroke="#000000" stroke-width="0.24000000000000005">
                      <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                      <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                      <g id="SVGRepo_iconCarrier">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                          d="M12.2929 4.29289C12.6834 3.90237 13.3166 3.90237 13.7071 4.29289L20.7071 11.2929C21.0976 11.6834 21.0976 12.3166 20.7071 12.7071L13.7071 19.7071C13.3166 20.0976 12.6834 20.0976 12.2929 19.7071C11.9024 19.3166 11.9024 18.6834 12.2929 18.2929L17.5858 13H4C3.44772 13 3 12.5523 3 12C3 11.4477 3.44772 11 4 11H17.5858L12.2929 5.70711C11.9024 5.31658 11.9024 4.68342 12.2929 4.29289Z"
                          fill="#000000"></path>
                      </g>
                    </svg>
                  </a>
                </div> --}}
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </section>
