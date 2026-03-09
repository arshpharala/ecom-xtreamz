<!DOCTYPE html>
<html lang="en" style="direction:{{ locale()->direction ?? 'ltr' }}">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Basic Meta -->
  <meta charset="UTF-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="robots" content="index, follow" />

  {!! render_meta_tags($meta ?? null) !!}

  <meta name="currency" content="{{ active_currency(true)->code }}">
  <meta name="currency-symbol" content="{{ active_currency(true)->symbol }}">
  <meta name="currency-position" content="{{ active_currency(true)->currency_position }}">
  <meta name="decimal" content="{{ active_currency(true)->decimal }}">
  <meta name="decimal-separator" content="{{ active_currency(true)->decimal_separator }}">
  <meta name="group-separator" content="{{ active_currency(true)->group_separator }}">


  <!-- Canonical URL -->
  <link rel="canonical" href="https://www.xtremez.com/" />

  <!-- Favicon -->
  <link rel="icon" href="{{ asset(setting('site_favicon', 'theme/xtremez/assets/icons/apple-touch-icon.png')) }}"
    type="image/png">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  {{-- <link rel="icon" type="image/png" sizes="32x32"
    href="{{ asset('theme/xtremez/assets/icons//favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16"
    href="{{ asset('theme/xtremez/assets/icons//favicon-16x16.png') }}"> --}}


  <style>
    @font-face {
      font-family: 'UAESymbol';
      src: url('{{ asset('assets/fonts/font.woff2') }}') format('woff2'),
        url('{{ asset('assets/fonts/font.woff') }}') format('woff'),
        url('{{ asset('assets/fonts/font.ttf') }}') format('truetype');
    }

    .dirham-symbol {
      font-family: 'UAESymbol', sans-serif;
      font-size: inherit;
      color: inherit;
      /* font-weight: bold; */
    }
  </style>
  @stack('head')



  <!-- Your compiled SCSS output -->
  <link href="{{ asset('theme/store/assets/css/main.css') }}" rel="stylesheet">

</head>

<body>

  <div class="site-wrapper d-flex flex-column min-vh-100">


    <section class="announcement">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center">

          <!-- Swiper -->
          <div class="swiper announcement-swiper flex-grow-1">
            <div class="swiper-wrapper">
              <div class="swiper-slide">
                <span class="ms-2 announcement-text">Ready Stocks in UAE, Saudi Arabia, Qatar, South Africa and India |
                  Aspirational
                  Brands & Genuine Sustainable Products </span>
              </div>

            </div>
          </div>

          <!-- User Menu -->

          <a href="{{ route('customers.profile') }}#wishlist"
            class="nav-link ms-3 text-nowrap d-none d-md-flex align-items-center">
            <i class="bi bi-heart icon"></i> Wishlist</a>
          <div class="divider text-white ms-3">|</div>

          <a href="{{ route('customers.profile') }}"
            class="nav-link ms-3 text-nowrap d-none d-md-flex align-items-center"><i class="bi bi-person icon"></i>My
            Account</a>

        </div>
      </div>
    </section>




  </div>

  <script>
    const appUrl = "{{ url('') }}";
  </script>


  <!-- Bootstrap 5 Bundle JS (includes Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script src="{{ asset('assets/js/currency.js') }}"></script>

  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Select2 -->
  <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <!-- Owl Carousel JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

  @stack('scripts')

  <!-- custom JS -->

  <script src="{{ asset('theme/xtremez/assets/js/main.js') }}"></script>
  <script src="{{ asset('theme/xtremez/assets/js/idb-handler.js') }}"></script>
  <script src="{{ asset('theme/xtremez/assets/js/cart.js') }}"></script>
  <script src="{{ asset('assets/js/form.js') }}"></script>



  <script>
    document.addEventListener('DOMContentLoaded', function() {
      new Swiper('.announcement-swiper', {
        loop: true,
        autoplay: {
          delay: 3000,
          disableOnInteraction: false,
        },
        slidesPerView: 1,
        allowTouchMove: false, // announcements shouldn't be draggable
        speed: 500,
      });
    });
  </script>

  <script>
    (function() {
      const btn = document.getElementById('scrollTopBtn');
      if (!btn) return;

      const showAfter = 200; // px scrolled before showing

      function toggle() {
        if (window.scrollY > showAfter) {
          btn.classList.add('is-visible');
        } else {
          btn.classList.remove('is-visible');
        }
      }

      function scrollToTop() {
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (prefersReduced) {
          window.scrollTo(0, 0);
        } else {
          window.scrollTo({
            top: 0,
            behavior: 'smooth'
          });
        }
      }

      window.addEventListener('scroll', toggle, {
        passive: true
      });
      btn.addEventListener('click', scrollToTop);

      // initial state
      toggle();
    })();
  </script>


</body>

</html>
