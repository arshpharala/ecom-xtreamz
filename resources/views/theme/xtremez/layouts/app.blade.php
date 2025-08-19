<!DOCTYPE html>
<html lang="en" style="direction:{{ locale()->direction ?? 'ltr' }}">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Basic Meta -->
  <meta charset="UTF-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="robots" content="index, follow" />
  <meta name="description"
    content="Xtremez is the Middle East’s leader in corporate gifts, promotional products, and sustainable branded giveaways. Discover innovative gifting solutions.">
  <meta name="keywords" content="Corporate Gifts, Promotional Products, Sustainable Giveaways, Custom Gifts, Xtremez">
  <meta name="author" content="Xtremez" />

  <meta name="currency" content="{{ active_currency(true)->code }}">
  <meta name="currency-symbol" content="{{ active_currency(true)->symbol }}">
  <meta name="currency-position" content="{{ active_currency(true)->currency_position }}">
  <meta name="decimal" content="{{ active_currency(true)->decimal }}">
  <meta name="decimal-separator" content="{{ active_currency(true)->decimal_separator }}">
  <meta name="group-separator" content="{{ active_currency(true)->group_separator }}">
  <style>
    @font-face {
      font-family: 'UAESymbol';
      src: url('assets/font/font.ttf') format('truetype');
    }

    .uae-symbol {
      font-family: 'UAESymbol';
      margin-right: 5px;
      color: 'black';
      /* Ensure symbol follows theme */
    }
  </style>

  <title>Corporate Gifts & Promotional Items in UAE | Xtreme
    Gifting</title>

  <!-- Canonical URL -->
  <link rel="canonical" href="https://www.xtremez.com/" />

  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="180x180"
    href="{{ asset(setting('site_favicon', 'theme/xtremez/assets/icons/apple-touch-icon.png')) }}" />
  <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />

  {{-- <link rel="icon" type="image/png" sizes="32x32"
    href="{{ asset('theme/xtremez/assets/icons//favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16"
    href="{{ asset('theme/xtremez/assets/icons//favicon-16x16.png') }}"> --}}


  @stack('head')

  <!-- Your compiled SCSS output -->
  <link href="{{ asset('theme/xtremez/assets/css/main.css') }}" rel="stylesheet">

</head>

<body>

  <div class="site-wrapper d-flex flex-column min-vh-100">


    @include('theme.xtremez.layouts.announcement')

    @include('theme.xtremez.layouts.header')

    <main class="flex-fill">

      @yield('breadcrumb')

      @yield('content')


    </main>

    @include('theme.xtremez.layouts.footer')
    @include('theme.xtremez.layouts.scrolltop')

    <style>
      #sendbtn,
      #sendbtn2,
      .wa-order-button,
      .gdpr_wa_button_input {
        background-color: #25d366 !important;
        color: rgba(255, 255, 255, 1) !important;
      }

      .floating_button {
        right: 20px;
        position: fixed !important;
        width: 60px !important;
        height: 60px !important;
        bottom: 20px !important;
        background-color: #25D366 !important;
        color: #ffffff !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        box-shadow: 0 8px 25px -5px rgba(45, 62, 79, .3) !important;
        z-index: 9999999 !important;
        text-decoration: none !important;
      }

      .floating_button .floating_button_icon {
        display: block !important;
        width: 30px !important;
        height: 30px !important;
        background-image: url(data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="30px" height="30px"><path fill="%23fff" d="M3.516 3.516c4.686-4.686 12.284-4.686 16.97 0 4.686 4.686 4.686 12.283 0 16.97a12.004 12.004 0 01-13.754 2.299l-5.814.735a.392.392 0 01-.438-.44l.748-5.788A12.002 12.002 0 013.517 3.517zm3.61 17.043l.3.158a9.846 9.846 0 0011.534-1.758c3.843-3.843 3.843-10.074 0-13.918-3.843-3.843-10.075-3.843-13.918 0a9.846 9.846 0 00-1.747 11.554l.16.303-.51 3.942a.196.196 0 00.219.22l3.961-.501zm6.534-7.003l-.933 1.164a9.843 9.843 0 01-3.497-3.495l1.166-.933a.792.792 0 00.23-.94L9.561 6.96a.793.793 0 00-.924-.445 1291.6 1291.6 0 00-2.023.524.797.797 0 00-.588.88 11.754 11.754 0 0010.005 10.005.797.797 0 00.88-.587l.525-2.023a.793.793 0 00-.445-.923L14.6 13.327a.792.792 0 00-.94.23z"/></svg>) !important;
        background-repeat: no-repeat !important;
        background-position: center !important;
        background-size
      }
    </style>

    <a id="sendbtn"
      href="whatsapp://send?phone=971582242212&amp;text=Hi%20Merchlist%2C%20I%20need%20to%20inquire%20about%3A%0A%0A%2A%3A%2A%20https%3A%2F%2Fthemerchlist.com%2F&amp;app_absent=0"
      role="button" target="_blank" class="floating_button">
      <span class="floating_button_icon"></span>
      <div class="label-container">
        <div class="label-text">Need help? Chat with us</div>
      </div>
    </a>
  </div>

  <script>
    const appUrl = "{{ env('APP_URL') }}";
  </script>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

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
