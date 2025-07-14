@extends('theme.xtremez.layouts.app')
@push('head')
  <!-- Owl Carousel CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
@endpush
@section('content')
  <main class="flex-fill">

    <section class="hero-section d-flex align-items-center p-0">
      <div class="owl-carousel hero-carousel w-100">
        <!-- Slide 1 -->
        <div class="hero-slide" style="background-image: url({{ asset('theme/xtremez/assets/images/banner.jpg') }});">
          <div class="container">
            <div class="row">
              <div class="col-lg-6 text-white hero-text animate-on-scroll" data-animate="fade-up">
                <h2 class="hero-subtitle">Corporate</h2>
                <h1 class="hero-title">Gift Items</h1>
                <p class="hero-description">
                  Xtremez is the Middle East's largest
                  and leading corporate gifts supplier
                  and solution provider, with deep
                  expertise in branded merchandise,
                  corporate giveaways and promotional
                  gifts and giveaways.
                </p>
                <a href="#" class="btn btn-light btn-lg mt-3">Browse
                  More</a>
              </div>
            </div>
          </div>

          <div class="feature-list d-none d-lg-flex">
            <div class="feature-dash"></div>
            <div><span>Creative</span></div>
            <div><span>Innovative</span></div>
            <div><span>Sustainable</span></div>
          </div>

        </div>
        <!-- Slide 2 -->
        <div class="hero-slide" style="background-image: url({{ asset('theme/xtremez/assets/images/banner-2.jpg') }});">
          <div class="container">
            <div class="row">
              <div class="col-lg-6 text-white hero-text">
                <h2 class="hero-subtitle">Personalized</h2>
                <h1 class="hero-title">Business
                  Gifts</h1>
                <p class="hero-description">
                  Make your brand unforgettable with
                  personalized gifts. High-quality
                  products, fast delivery, and custom
                  branding!
                </p>
                <a href="#" class="btn btn-light btn-lg mt-3">Shop
                  Now</a>
              </div>
            </div>
          </div>
          <div class="feature-list d-none d-lg-flex">
            <div class="feature-dash"></div>
            <div><span>Creative</span></div>
            <div><span>Innovative</span></div>
            <div><span>Sustainable</span></div>
          </div>
        </div>
        <!-- Add more .hero-slide divs as needed -->
      </div>
    </section>

    <!-- <section class="hero-section d-flex align-items-center">
                                                  <div class="container">
                                                      <div class="row">
                                                          <div
                                                              class="col-lg-6 text-white hero-text animate-on-scroll"
                                                              data-animate="fade-up">
                                                              <h2 class="hero-subtitle">Corporate</h2>
                                                              <h1 class="hero-title">Gift Items</h1>
                                                              <p class="hero-description">
                                                                  Xtremez is the Middle East's largest and
                                                                  leading
                                                                  corporate gifts
                                                                  supplier and solution provider,
                                                                  with deep expertise in branded merchandise,
                                                                  corporate giveaways
                                                                  and promotional gifts and giveaways.
                                                              </p>
                                                              <a href="#"
                                                                  class="btn btn-light btn-lg mt-3">Browse
                                                                  More</a>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </section> -->

    <!-- Search Section -->
    <div class="search-bar-wrapper">
      <div class="container">
        <form action="search.html" method="get">

          <div class="search-bar d-flex align-items-center shadow  animate-on-scroll" data-animate="fade-down">
            <input type="text" class="form-control border-0 type-placeholder" name="q"
              placeholder="Search Products">
            <button class="btn btn-link text-dark p-0 pe-3 no-animate">
              <i class="bi bi-search fs-2"></i>
            </button>
          </div>
        </form>
      </div>
    </div>

    <section class="heading-section py-5">
      <div class="container">
        <div class="heading-row animate-on-scroll" data-animate="fade-up">
          <h2 class="section-title fs-1 text-center m-0">Browse
            by
            Category</h2>
        </div>
      </div>
    </section>

    <section class="browse-categories py-5">
      <div class="container text-center">
        <div class="category-icons d-flex justify-content-center align-items-center flex-wrap pb-5 animate-on-scroll"
          data-animate="fade-up">

          @foreach ($categories as $category)
            <div class="category-item" data-category="{{ $category->name }}">
              <img src="{{ asset('storage/' . $category->icon) }}" alt="{{ $category->name }} icon"
                class="category-icon {{ $loop->first ? 'active' : '' }}">
            </div>
            @if ($loop->last == false)
              <span class="dot-separator">•</span>
            @endif
          @endforeach

        </div>
      </div>
    </section>

    <section class="product-section pb-5 animate-on-scroll" data-animate="fade-up">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h2 class="section-title fs-1">Clothing</h2>
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

    <section class="gift-set-section py-5">
      <div class="container">
        <div class="row g-0 gift-set-grid">
          <!-- LEFT: Full-height box -->
          <div class="col-lg-6 animate-on-scroll" data-animate="fade-right">
            <div class="gift-box h-100 gift-left position-relative  p-4">
              <div class="z-2 px-3">
                <h2 class="mb-3">Gift Set</h2>

              </div>
              <div class="z-2 d-flex justify-content-between px-3">
                <p class="fs-5 fw-semibold mt-3">
                  SKROSS - Gift Set of Powerbank,
                  Travel
                  Adapter & Charging
                  Cable
                </p>
                <p class="fs-2 fw-normal mt-3 z-2 align-self-end">89
                  AED</p>

              </div>
              <img src="{{ asset('theme/xtremez/assets/images/gift-set-left.png') }}" alt="Gift Set"
                class="gift-img img-left-big">
            </div>
          </div>

          <!-- RIGHT: Two stacked half-height boxes -->
          <div class="col-lg-6 d-flex flex-column animate-on-scroll" data-animate="fade-left">
            @foreach (collect($giftSetProducts)->skip(1)->take(2) as $product)
              @if ($loop->first)
                <div
                  class="gift-box gift-right-half bg-lightblue flex-fill position-relative text-white p-4 d-flex flex-column justify-content-between">
                  <div class="z-2 px-4">
                    <p class="fs-5 fw-bold my-3">{{ $product->name }}</p>
                    <p class="fs-2 fw-normal">{{ $product->price }} {{ active_currency() }}</p>
                  </div>
                  <img src="{{ asset($product->image) }}" alt="TWS" class="gift-img img-right-small">
                </div>
              @else
                <!-- Bottom box -->
                <div
                  class="gift-box gift-right-half bg-tan flex-fill position-relative text-white p-4 d-flex flex-column justify-content-between">
                  <img src="{{ asset($product->image) }}" alt="Gift Box" class="gift-img img-left-small">
                  <div class="z-2 text-start px-4">
                    <p class="fs-5 fw-bold my-3">{{ $product->name }}</p>
                    <p class="fs-2 fw-normal">{{ $product->price }} {{ active_currency() }}</p>
                  </div>
                </div>
              @endif
            @endforeach


          </div>
        </div>
      </div>
    </section>

    <section class="heading-section py-5">
      <div class="container">
        <div class="heading-row  animate-on-scroll" data-animate="fade-down">
          <h2 class="section-title fs-1 text-center m-0">Featured
            Products</h2>
        </div>
      </div>
    </section>

    <section class="product-section py-5 animate-on-scroll" data-animate="fade-up">
      <div class="container">
        <div class="row g-4">
          <!-- Product Card Start -->
          <div class="col-12 col-sm-6 col-md-6  col-lg-4 col-xl-3">
            <div class="product-card d-flex flex-column">
              <div class="image-box">
                <img src="{{ asset('theme/xtremez/assets/images/product-1.png') }}" alt />
              </div>
              <div class="image_overlay">
              </div>
              <div class="overlay-button">View
                details</div>
              <div class="stats-container">
                <span class="product-title">BREDA -
                  CHANGE Collection
                  Insulated Water
                  Bottle - Green</span>
                <div class="product-description">
                  <p>
                    1pc G13 VR Headset - Immersive
                    Virtual Reality
                    Experience with High-Quality
                  </p>
                </div>
                <!-- Price + Cart -->
                <div class="product-meta">
                  <span class="price fs-4 fw-bold">70
                    AED</span>
                  <button class="btn cart-btn">
                    <i class="bi bi-cart"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <!-- Product Card End -->
          <!-- Product Card Start -->
          <div class="col-12 col-sm-6 col-md-6  col-lg-4 col-xl-3">
            <div class="product-card d-flex flex-column">
              <div class="image-box">
                <img src="{{ asset('theme/xtremez/assets/images/product-1.png') }}" alt />
              </div>
              <div class="image_overlay"></div>
              <div class="overlay-button">View
                details</div>
              <div class="stats-container">
                <span class="product-title">BREDA -
                  CHANGE Collection
                  Insulated Water
                  Bottle - Green</span>
                <div class="product-description">
                  <p>
                    1pc G13 VR Headset - Immersive
                    Virtual Reality
                    Experience with High-Quality
                  </p>
                </div>
                <!-- Price + Cart -->
                <div class="product-meta">
                  <span class="price fs-4 fw-bold">70
                    AED</span>
                  <button class="btn cart-btn">
                    <i class="bi bi-cart"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <!-- Product Card End -->
          <!-- Product Card Start -->
          <div class="col-12 col-sm-6 col-md-6  col-lg-4 col-xl-3">
            <div class="product-card d-flex flex-column">
              <div class="image-box">
                <img src="{{ asset('theme/xtremez/assets/images/product-1.png') }}" alt />
              </div>
              <div class="image_overlay"></div>
              <div class="overlay-button">View
                details</div>
              <div class="stats-container">
                <span class="product-title">BREDA -
                  CHANGE Collection
                  Insulated Water
                  Bottle - Green</span>
                <div class="product-description">
                  <p>
                    1pc G13 VR Headset - Immersive
                    Virtual Reality
                    Experience with High-Quality
                  </p>
                </div>
                <!-- Price + Cart -->
                <div class="product-meta">
                  <span class="price fs-4 fw-bold">70
                    AED</span>
                  <button class="btn cart-btn">
                    <i class="bi bi-cart"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <!-- Product Card End -->
          <!-- Product Card Start -->
          <div class="col-12 col-sm-6 col-md-6  col-lg-4 col-xl-3">
            <div class="product-card d-flex flex-column">
              <div class="image-box">
                <img src="{{ asset('theme/xtremez/assets/images/product-1.png') }}" alt />
              </div>
              <div class="image_overlay"></div>
              <div class="overlay-button">View
                details</div>
              <div class="stats-container">
                <span class="product-title">BREDA -
                  CHANGE Collection
                  Insulated Water
                  Bottle - Green</span>
                <div class="product-description">
                  <p>
                    1pc G13 VR Headset - Immersive
                    Virtual Reality
                    Experience with High-Quality
                  </p>
                </div>
                <!-- Price + Cart -->
                <div class="product-meta">
                  <span class="price fs-4 fw-bold">70
                    AED</span>
                  <button class="btn cart-btn">
                    <i class="bi bi-cart"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <!-- Product Card End -->
          <!-- Product Card Start -->
          <div class="col-12 col-sm-6 col-md-6  col-lg-4 col-xl-3">
            <div class="product-card d-flex flex-column">
              <div class="image-box">
                <img src="{{ asset('theme/xtremez/assets/images/product-1.png') }}" alt />
              </div>
              <div class="image_overlay"></div>
              <div class="overlay-button">View
                details</div>
              <div class="stats-container">
                <span class="product-title">BREDA -
                  CHANGE Collection
                  Insulated Water
                  Bottle - Green</span>
                <div class="product-description">
                  <p>
                    1pc G13 VR Headset - Immersive
                    Virtual Reality
                    Experience with High-Quality
                  </p>
                </div>
                <!-- Price + Cart -->
                <div class="product-meta">
                  <span class="price fs-4 fw-bold">70
                    AED</span>
                  <button class="btn cart-btn">
                    <i class="bi bi-cart"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <!-- Product Card End -->
          <!-- Product Card Start -->
          <div class="col-12 col-sm-6 col-md-6  col-lg-4 col-xl-3">
            <div class="product-card d-flex flex-column">
              <div class="image-box">
                <img src="{{ asset('theme/xtremez/assets/images/product-1.png') }}" alt />
              </div>
              <div class="image_overlay"></div>
              <div class="overlay-button">View
                details</div>
              <div class="stats-container">
                <span class="product-title">BREDA -
                  CHANGE Collection
                  Insulated Water
                  Bottle - Green</span>
                <div class="product-description">
                  <p>
                    1pc G13 VR Headset - Immersive
                    Virtual Reality
                    Experience with High-Quality
                  </p>
                </div>
                <!-- Price + Cart -->
                <div class="product-meta">
                  <span class="price fs-4 fw-bold">70
                    AED</span>
                  <button class="btn cart-btn">
                    <i class="bi bi-cart"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <!-- Product Card End -->
          <!-- Product Card Start -->
          <div class="col-12 col-sm-6 col-md-6  col-lg-4 col-xl-3">
            <div class="product-card d-flex flex-column">
              <div class="image-box">
                <img src="{{ asset('theme/xtremez/assets/images/product-1.png') }}" alt />
              </div>
              <div class="image_overlay"></div>
              <div class="overlay-button">View
                details</div>
              <div class="stats-container">
                <span class="product-title">BREDA -
                  CHANGE Collection
                  Insulated Water
                  Bottle - Green</span>
                <div class="product-description">
                  <p>
                    1pc G13 VR Headset - Immersive
                    Virtual Reality
                    Experience with High-Quality
                  </p>
                </div>
                <!-- Price + Cart -->
                <div class="product-meta">
                  <span class="price fs-4 fw-bold">70
                    AED</span>
                  <button class="btn cart-btn">
                    <i class="bi bi-cart"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <!-- Product Card End -->
          <!-- Product Card Start -->
          <div class="col-12 col-sm-6 col-md-6  col-lg-4 col-xl-3">
            <div class="product-card d-flex flex-column">
              <div class="image-box">
                <img src="{{ asset('theme/xtremez/assets/images/product-1.png') }}" alt />
              </div>
              <div class="image_overlay"></div>
              <div class="overlay-button">View
                details</div>
              <div class="stats-container">
                <span class="product-title">BREDA -
                  CHANGE Collection
                  Insulated Water
                  Bottle - Green</span>
                <div class="product-description">
                  <p>
                    1pc G13 VR Headset - Immersive
                    Virtual Reality
                    Experience with High-Quality
                  </p>
                </div>
                <!-- Price + Cart -->
                <div class="product-meta">
                  <span class="price fs-4 fw-bold">70
                    AED</span>
                  <button class="btn cart-btn">
                    <i class="bi bi-cart"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <!-- Product Card End -->
        </div>
      </div>
    </section>

    <section class="sustainable-section py-5">
      <div class="container-fluid px-0 bg-beige">
        <div class="row g-0 align-items-center">

          <!-- Left Text Column -->
          <div class="sustainable-content-section col-md-5 px-lg-5 text-center text-md-start">
            <div class="sustainable-content px-3 px-xl-5 animate-on-scroll" data-animate="fade-right">
              <h2 class="sustainable-heading fw-bold mb-4">Sustainable<br>Products</h2>
              <a href="#" class="btn btn-secondary rounded-2">View
                Items</a>
            </div>
          </div>

          <!-- Right Image Column -->
          <div class="col-md-7 sustainable-bg-image">
            <div class="sustainable-image animate-on-scroll" data-animate="fade-left">
            </div>
          </div>
          <!-- <div class="col-md-6">
                                                              <div class="sustainable-image animate-on-scroll"
                                                                  data-animate="fade-left">
                                                                  <img src="assets/images/sustainable.png"
                                                                      alt="Sustainable Products"
                                                                      class="img-fluid w-100 h-100 object-fit-cover">
                                                              </div>
                                                          </div> -->

        </div>
      </div>
    </section>

    <section class="heading-section py-5">
      <div class="container">
        <div class="heading-row  animate-on-scroll" data-animate="fade-down">
          <h2 class="section-title fs-1 text-center m-0">Our
            Brands</h2>
        </div>
      </div>
    </section>

    <section class="our-brands-section py-5">
      <div class="container">
        <div class="slider pb-5">
          @for ($i = 0; $i < 2; $i++)
            <div class="logos">
              @foreach ($brands as $brand)
                <img src="{{ asset('storage/' . $brand->logo) }}" class="brand-logo" alt="{{ $brand->name }}" />
              @endforeach
            </div>
          @endfor
        </div>
      </div>
    </section>

    <!-- <section class="our-brands-section pb-5">
                                                  <div class="container text-center">
                                                      <div
                                                          class="row justify-content-center align-items-center g-4 py-5">
                                                          <div class="col-4 col-md-2 animate-on-scroll"
                                                              data-animate="fade-up">
                                                              <img src="{{ asset('theme/xtremez/assets/images/brands/adidas.png') }}"
                                                                  alt="Adidas"
                                                                  class="brand-logo img-fluid">
                                                          </div>
                                                          <div class="col-4 col-md-2  animate-on-scroll"
                                                              data-animate="fade-up">
                                                              <img src="{{ asset('theme/xtremez/assets/images/brands/boss.png') }}"
                                                                  alt="Boss"
                                                                  class="brand-logo img-fluid">
                                                          </div>
                                                          <div class="col-4 col-md-2  animate-on-scroll"
                                                              data-animate="fade-up">
                                                              <img src="{{ asset('theme/xtremez/assets/images/brands/chanel.png') }}"
                                                                  alt="Chanel"
                                                                  class="brand-logo img-fluid">
                                                          </div>
                                                          <div class="col-4 col-md-2  animate-on-scroll"
                                                              data-animate="fade-up">
                                                              <img src="{{ asset('theme/xtremez/assets/images/brands/boss.png') }}"
                                                                  alt="Boss 2"
                                                                  class="brand-logo img-fluid">
                                                          </div>
                                                          <div class="col-4 col-md-2  animate-on-scroll"
                                                              data-animate="fade-up">
                                                              <img src="{{ asset('theme/xtremez/assets/images/brands/ikasu.png') }}"
                                                                  alt="Ikasu"
                                                                  class="brand-logo img-fluid">
                                                          </div>
                                                      </div>
                                                  </div>
                                              </section> -->

    <section class="video-section position-relative py-5">
      <div class="video-container">
        <div class="video-bg">
          <img src="{{ asset('theme/xtremez/assets/images/video-thumbnail.png') }}" alt="Video Background"
            class="w-100 h-100 object-fit-cover">
        </div>

        <div class="video-overlay position-absolute top-0 start-0 w-100 h-100"></div>

        <button class="video-play-btn position-absolute top-50 start-50 translate-middle" aria-label="Play Video"
          data-toggle="modal" data-target="#exampleModalCenter">
          <i class="bi bi-play-fill"></i>
        </button>

      </div>
    </section>

    <section class="gift-collection-section pb-5">
      <div class="container py-5">
        <div class="collection-card text-center position-relative overflow-hidden">
          <img src="{{ asset('theme/xtremez/assets/images/gift-collection.png') }}" alt="Gifts"
            class="collection-bg w-100 h-100 object-fit-cover">

          <div
            class="collection-overlay position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center text-white">
            <i class="bi bi-book mb-3 animate-on-scroll" data-animate="fade-down"></i>
            <h2 class="collection-title text-uppercase mb-4 animate-on-scroll" data-animate="fade-down">Our
              2024 Gifts<br>Collection</h2>
            <a href="#" class="btn btn-light animate-on-scroll" data-animate="fade-up">View
              Brochure</a>
          </div>
        </div>
      </div>
    </section>

  </main>
@endsection

@push('scripts')
  <!-- Owl Carousel JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
  <script src="{{ asset('theme/xtremez/assets/js/product-carousel.js') }}"></script>
  <script>
    $(document).ready(function() {
      $('.hero-carousel').owlCarousel({
        items: 1,
        loop: true,
        autoplay: true,
        autoplayTimeout: 3000,
        nav: false,
        animateOut: 'fadeOut',
        animateIn: 'fadeIn'
      });
    });
  </script>
@endpush
