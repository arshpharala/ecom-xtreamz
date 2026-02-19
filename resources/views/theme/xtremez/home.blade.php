@extends('theme.xtremez.layouts.app')
@section('content')
  <main class="flex-fill">

    <section class="hero-section d-flex align-items-center p-0">
      <div class="owl-carousel hero-carousel w-100">
        <!-- Slide 1 -->
        @foreach ($banners as $banner)
          <div class="hero-slide" style="background: url({{ asset('storage/' . $banner->background) }});"
            data-background="{{ asset('storage/' . $banner->background) }}">
            <div class="container">
              <div class="row">

                <div class="col-lg-4">
                  <div class="slider-img d-none d-lg-block" data-animation="fadeInRight" data-delay=".8s">
                    <img src="{{ asset('storage/' . $banner->image) }}" alt="">
                  </div>
                </div>
                <div class="col-lg-8 text-white hero-text animate-on-scroll" data-animate="fade-up">
                  <h1 class="hero-title" style="color: {{ $banner->text_color }}">{{ $banner->translation->title }}</h1>
                  @if ($banner->btn_link)
                    <a href="{{ $banner->btn_link }}" class="mt-3 buy-link"
                      style="background: {{ $banner->btn_color }}">{{ $banner->btn_text ?? __('Shop Now') }}</a>
                  @endif
                </div>
              </div>
            </div>

          </div>
        @endforeach

      </div>
    </section>

      <section class="features-strip-section">
    <div class="container">
      <div class="features-strip">
        <div class="row gap-3 align-items-center text-start">

          <div class="col-12 col-sm-6 col-md-5 col-lg d-flex">
            <div class="item w-100">
              <div class="icon-wrap"><i class="bi bi-truck"></i></div>
              <div>
                <div class="title">Easy Free Delivery</div>
                <p class="sub">Orders Above 100 AED</p>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-md-5 col-lg d-flex">
            <div class="item w-100">
              <div class="icon-wrap"><i class="bi bi-shield-check"></i></div>
              <div>
                <div class="title">Secure Payments</div>
                <p class="sub">Trusted payment options.</p>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-md-5 col-lg d-flex">
            <div class="item w-100">
              <div class="icon-wrap"><i class="bi bi-recycle"></i></div>
              <div>
                <div class="title">Easy Returns</div>
                <p class="sub">Fast and easy returns</p>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-md-5 col-lg d-flex">
            <div class="item w-100">
              <div class="icon-wrap"><i class="bi bi-headset"></i></div>
              <div>
                <div class="title">Customer Support</div>
                <p class="sub">Expert Assistance</p>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

    <!-- Search Section -->
    <div class="search-bar-wrapper">
      <div class="container">
        <form action="{{ route('search') }}" method="get">

          <div class="search-bar d-flex align-items-center shadow  animate-on-scroll" data-animate="fade-down">
            <input type="text" class="form-control border-0 type-placeholder" name="q"
              placeholder="Search Products" required>
            <button class="btn btn-link text-dark p-0 pe-3 no-animate">
              <i class="bi bi-search fs-2"></i>
            </button>
          </div>
        </form>
      </div>
    </div>

    <section class="heading-section py-5">
      <div class="container">
        <div class="heading-row">
          <h2 class="section-title fs-1 text-center m-0 text-uppercase">Browse by Category</h2>
        </div>
        <p class="text-center animate-on-scroll" data-animate="fade-up">Explore our store the easy way: shop by category
          and enjoy a seamless, organized shopping
          experience.</p>
      </div>
    </section>


    <section class="promo-tiles py-3">
      <div class="container">
        <div class="row g-3 g-lg-4 d-flex animate-on-scroll" data-animate="zoom-out">
          @foreach ($categories as $category)
            <div class="col-12 col-lg-4 flex-fill">
              <a href="{{ $category->link }}" class="promo-tile" style="background: {{ $category->background_color }}">
                <div class="promo-copy d-flex flex-column h-100 justify-content-around">
                  <h3 class="title">{{ $category->name }}</h3>
                  <span class="cta">Shop Now</span>
                  <div class="btn-circle" style="margin: 50px 0 0 0 ">
                    <i class="bi bi-arrow-right fw-bold"></i>
                  </div>
                </div>
                <img src="{{ $category->image }}" alt="{{ $category->name }}" class="promo-img">
              </a>
            </div>
          @endforeach
        </div>
      </div>
    </section>

    @include('theme.xtremez.components.products.carousel', [
        'id' => 'new',
        'sectionName' => 'New Arrivals',
        'productUrl' => route('ajax.get-products', ['tags' => ['New Arrival']]),
    ])

    @foreach ($promoOffers->take(3) as $tile)
      <section class="offer-banner py-5">
        <div class="container-fluid">
          <div class="row" style="background: {{ $tile->bg }}">
            <div class="col-md-12">
              <div class="container">
                <div class="row">
                  <div class="col-md-6 col-lg-5 d-flex flex-row">
                    <div class="title-area">
                      <h3 class="title">Get
                        <span>
                          {{ $tile->title }}
                        </span>
                      </h3>
                      <div class="action-area w-100 d-none d-md-flex d-lg-none">
                        <a href="http://">Shop Now</a>
                      </div>

                    </div>
                  </div>
                  <div class="col-md-6 col-lg-4 d-flex flex-row">
                    <img src="{{ $tile->image }}" alt="{{ $tile->title }}" class="promo-img">
                  </div>
                  <div class="col-md-3 col-lg-3 d-flex d-sm-none d-lg-flex flex-row">
                    <div class="action-area w-100">
                      <a href="http://">Shop Now</a>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    @break
  @endforeach

  @include('theme.xtremez.components.products.gift-set', ['products' => $giftSetProducts])

  {{-- @include('theme.xtremez.components.products.carousel', [
      'id' => 'featured',
      'sectionName' => 'Featured Products',
      'productUrl' => route('ajax.get-products', ['tags' => ['On Sale', 'On Sale / Clearance Items']]),
  ]) --}}


  <section class="sustainable-section py-5 position-relative">
    <div class="container position-relative">
      <div class="sustainable-banner d-flex align-items-center justify-content-between flex-wrap">

        <!-- Left: Text -->
        <div class="sustainable-text">
          <p class="subtitle">Eco Collection</p>
          <h2 class="title">Sustainable<br>Products</h2>
          <a href="{{ route('products', ['tags' => ['Sustainable']]) }}" class="cta-link">Shop Now</a>
        </div>

      </div>

      <!-- Globe Icon -->
      <img src="{{ asset('assets/images/globe.png') }}" alt="Eco Icon" class="eco-icon">

      <img src="{{ asset('assets/images/sustainable-products.png') }}" alt="Sustainable Products"
        class="sustainable-image img-fluid">
    </div>
  </section>



  @include('theme.xtremez.components.brands', ['brands' => $brands])

  <style>

  </style>

  <section class="video py-5 overflow-hidden">
    <div class="container">
      <div class="row">
        <div class="video-content open-up aos-init aos-animate" data-aos="zoom-out">

          <div class="video-bg position-relative">
            <img src="{{ asset('assets/images/video-image.jpg') }}" alt="video" class="video-image img-fluid">

            <!-- Play Button Overlay -->
            <div class="video-player">
              <a class="youtube cboxElement" href="https://www.youtube.com/embed/pjtsGzQjFM4">
                <svg width="70" height="70" viewBox="0 0 24 24" fill="white">
                  <path d="M8 5v14l11-7z"></path>
                </svg>
              </a>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

  {{-- <section class="gift-collection-section pb-5">
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
    </section> --}}

  <section class="brochure-section py-5">
    <div class="container">
      <div class="brochure-box">
        <div class="row g-0 align-items-center">

          <!-- Left: Image -->
          <div class="col-lg-6 brochure-img">
            <img src="{{ asset('assets/images/corporate-gifts-giftana.png') }}" alt="Brochure" class="img-fluid">
          </div>

          <!-- Right: Text -->
          <div class="col-lg-6 brochure-text">
            <p class="subtitle">A wide collection of creatives</p>
            <h2 class="title">Our {{ today()->year }} Gifts<br>Collection</h2>
            <a href="#" class="btn brochure-btn">View Brochure</a>
          </div>

        </div>
      </div>
    </div>
  </section>


  @include('theme.xtremez.components.subscribe')



</main>
@endsection

@push('scripts')
<script>
  // Laravel route passed to JS
  window.ajaxProductURL = "{{ route('ajax.get-products') }}";
  window.activeCategoryId = "{{ $activeCategory->id ?? '' }}";
</script>

<script src="{{ asset('assets/js/home.js') }}"></script>

<script>
  $(document).ready(function() {
    $('.hero-carousel').owlCarousel({
      items: 1,
      loop: true,
      autoplay: true,
      autoplayTimeout: 3000,
      nav: true,
      dots: true,
      animateOut: 'fadeOut',
      animateIn: 'fadeIn'
    });


  });
</script>
@endpush
