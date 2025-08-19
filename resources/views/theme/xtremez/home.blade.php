@extends('theme.xtremez.layouts.app')
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
                <a href="{{ route('products') }}" class="btn btn-light btn-lg mt-3">Browse
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
        {{-- <div class="hero-slide" style="background-image: url({{ asset('theme/xtremez/assets/images/banner-2.jpg') }});">
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
        </div> --}}
        <!-- Add more .hero-slide divs as needed -->
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
        <div class="heading-row animate-on-scroll" data-animate="fade-up">
          <h2 class="section-title fs-1 text-center m-0 text-uppercase">Browse by Category</h2>
        </div>
        <p class="text-center">Explore our store the easy way: shop by category and enjoy a seamless, organized shopping
          experience.</p>
      </div>
    </section>

    <section class="browse-categories py-5">
      <div class="container text-center">
        <div class="category-icons row gx-4 gy-5 justify-content-center">

          @foreach ($categories as $category)
            <div class="col-4 col-sm-3 col-md-2 col-lg-2">
              <a href="#" class="category-item {{ $loop->first ? 'is-active' : 'is-active' }}"
                data-category-id="{{ $category->id }}" data-category="{{ $category->name }}"
                aria-label="{{ $category->name }}">
                <span class="category-badge">
                  <img src="{{ asset('storage/' . $category->icon) }}" alt="{{ $category->name }} icon" loading="lazy">
                </span>
                <span class="category-label text-uppercase">{{ $category->name }}</span>
              </a>
            </div>
          @endforeach

        </div>
      </div>
    </section>

    @include('theme.xtremez.components.products.carousel', [
        'id' => 'new',
        'sectionName' => 'New Arrivals',
        'productUrl' => route('ajax.get-products', ['is_new' => 1]),
    ])


    @if ($promoOffers->isNotEmpty())
      <section class="promo-tiles py-3">
        <div class="container">
          <div class="row g-3 g-lg-4">
            @foreach ($promoOffers as $tile)
              <div class="col-12 col-md-4">
                <a href="{{ $tile->url }}" class="promo-tile" style="background: {{ $tile->bg }}">
                  <div class="promo-copy">
                    @if ($tile->eyebrow)
                      <div class="eyebrow">{{ $tile->eyebrow }}</div>
                    @endif
                    <h3 class="title">{{ $tile->title }}</h3>
                    <span class="cta">Shop Now</span>
                  </div>
                  <img src="{{ $tile->image }}" alt="{{ $tile->title }}" class="promo-img">
                </a>
              </div>
            @endforeach
          </div>
        </div>
      </section>
    @endif




    @foreach ($categories as $category)
      @include('theme.xtremez.components.products.carousel', [
          'id' => $category->id,
          'sectionName' => $category->name,
          'productUrl' => route('ajax.get-products', ['category_id' => $category->id]),
      ])
    @endforeach

    @include('theme.xtremez.components.products.gift-set', ['products' => $giftSetProducts])

    @include('theme.xtremez.components.products.carousel', [
        'id' => 'featured',
        'sectionName' => 'Featured Products',
        'productUrl' => route('ajax.get-products', ['is_featured' => 1]),
    ])

    <section class="sustainable-section py-5">
      <div class="container px-0 bg-beige">
        <div class="row g-0 align-items-stretch sustainable-card"><!-- added hook -->

          <!-- Left Text Column -->
          <div class="sustainable-content-section col-md-6 px-lg-5 text-center text-md-start">
            <div class="sustainable-content px-3 px-xl-5 animate-on-scroll" data-animate="fade-right">
              <span class="sustainable-eyebrow">Eco Collection</span>
              <h2 class="sustainable-heading fw-bold mb-2">Sustainable<br>Products</h2>

              <div class="heading-accent mb-4"></div>

              <!-- optional sub copy -->
              <p class="sustainable-sub mb-4">Thoughtfully made from recycled materials. Durable design without
                compromising style.</p>

              <a href="{{ route('products') }}" class="btn btn-secondary">
                View Items
                <i class="bi bi-arrow-right-short"></i>
              </a>
            </div>
          </div>

          <!-- Right Image Column -->
          <div class="col-md-6 sustainable-bg-image">
            <div class="sustainable-image animate-on-scroll" data-animate="fade-left"></div>
          </div>
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

    <style>

    </style>

    <section class="video pt-5 overflow-hidden">
      <div class="container-fluid" style="max-width: 1800px">
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
        <div class="row">
          <div class="col-lg-6">
            <img src="{{ asset('assets/images/corporate-gifts-giftana.png') }}" alt="#">
          </div>
          <div class="col-lg-6">
            <div class="d-flex flex-column h-100 justify-content-center">
              <div class="section-title-area">
                <h1 class="section-title">Our 2024 Gifts
                  Collection</h1>
              </div>

              <div>
                <a href="#" class="btn btn-outline-dark text-uppercase">View Brochure</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="newsletter bg-light"
      style="background: url(https://themewagon.github.io/kaira/images/pattern-bg.png) repeat;">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-8 py-5 my-5">
            <div class="subscribe-header text-center pb-3">
              <h3 class="section-title text-uppercase">Sign Up for our newsletter</h3>
            </div>
            <form id="form" class="d-flex flex-wrap gap-2">
              <input type="text" name="email" placeholder="Your Email Addresss"
                class="form-control form-control-lg">
              <button class="btn btn-dark btn-lg text-uppercase w-100">Sign Up</button>
            </form>
          </div>
        </div>
      </div>
    </section>


    <section class="features-strip-section">
      <div class="container-fluid">
        <div class="features-strip">
          <div class="row gap-3 align-items-center text-start">

            <div class="col-12 col-sm-6 col-lg d-flex bg-white">
              <div class="item w-100">
                <div class="icon-wrap"><i class="bi bi-truck"></i></div>
                <div>
                  <div class="title">Easy Free Delivery</div>
                  <p class="sub">Orders Above 100 AED</p>
                </div>
              </div>
            </div>

            <div class="col-12 col-sm-6 col-lg d-flex bg-white">
              <div class="item w-100">
                <div class="icon-wrap"><i class="bi bi-shield-check"></i></div>
                <div>
                  <div class="title">Secure Payments</div>
                  <p class="sub">Trusted payment options.</p>
                </div>
              </div>
            </div>

            <div class="col-12 col-sm-6 col-lg d-flex bg-white">
              <div class="item w-100">
                <div class="icon-wrap"><i class="bi bi-recycle"></i></div>
                <div>
                  <div class="title">Easy Returns</div>
                  <p class="sub">Fast and easy returns</p>
                </div>
              </div>
            </div>

            <div class="col-12 col-sm-6 col-lg d-flex bg-white">
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
        nav: false,
        animateOut: 'fadeOut',
        animateIn: 'fadeIn'
      });


    });
  </script>
@endpush
