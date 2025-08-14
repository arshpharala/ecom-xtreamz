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
          <h2 class="section-title fs-1 text-center m-0">Browse by Category</h2>
        </div>
        <p class="text-center">Explore our store the easy way: shop by category and enjoy a seamless, organized shopping experience.</p>
      </div>
    </section>

    <section class="browse-categories py-5">
      <div class="container text-center">
        <div class="category-icons row gx-4 gy-5 justify-content-center">

          @foreach ($categories as $category)
            <div class="col-4 col-sm-3 col-md-2 col-lg-2">
              <a href="#"
                class="category-item {{ $loop->first ? 'is-active' : '' }}" data-category-id="{{ $category->id }}"
                data-category="{{ $category->name }}" aria-label="{{ $category->name }}">
                <span class="category-badge">
                  <img src="{{ asset('storage/' . $category->icon) }}" alt="{{ $category->name }} icon" loading="lazy">
                </span>
                <span class="category-label">{{ $category->name }}</span>
              </a>
            </div>
          @endforeach

        </div>
      </div>
    </section>


    {{-- <section class="browse-categories py-5">
      <div class="container text-center">
        <div class="category-icons d-flex justify-content-center align-items-center flex-wrap pb-5 animate-on-scroll"
          data-animate="fade-up">

          @foreach ($categories as $category)
            <div class="category-item" data-category-id="{{ $category->id }}" data-category="{{ $category->name }}">
              <img src="{{ asset('storage/' . $category->icon) }}" alt="{{ $category->name }} icon"
                class="category-icon {{ $loop->first ? 'active' : '' }}">
            </div>
            @if ($loop->last == false)
              <span class="dot-separator">•</span>
            @endif
          @endforeach

        </div>
      </div>
    </section> --}}

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

    @include('theme.xtremez.components.products.gift-set', ['products' => $giftSetProducts])

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
        <div class="row g-4" id="featured-products">
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
              <a href="{{ route('products') }}" class="btn btn-secondary rounded-2">View
                Items</a>
            </div>
          </div>

          <!-- Right Image Column -->
          <div class="col-md-7 sustainable-bg-image">
            <div class="sustainable-image animate-on-scroll" data-animate="fade-left">
            </div>
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


    <section class="features-strip-section pb-5">
      <div class="container">
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


      loadFeaturedProducts();

    });

    function loadFeaturedProducts() {
      $.ajax({
        url: "{{ route('ajax.get-products', ['is_featured' => true]) }}",
        method: "GET",
        dataType: "json",
        success: function(response) {
          if (response.success && response.data?.products?.length) {
            const html = response.data.products.map((product) =>
              render_product_card(product, "col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3")
            );
            $("#featured-products").html(html);
          } else {
            $("#featured-products").html(`<p class="text-muted">No featured products found.</p>`);
          }
        },
        error: function() {
          console.error("Failed to load featured products");
          $("#featured-products").html(`<p class="text-danger">Error loading products.</p>`);
        },
      });
    }
  </script>
@endpush
