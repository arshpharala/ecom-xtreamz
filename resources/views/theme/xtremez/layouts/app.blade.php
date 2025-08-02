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

  <title>Corporate Gifts & Promotional Items in UAE | Xtreme
    Gifting</title>

  <!-- Canonical URL -->
  <link rel="canonical" href="https://www.xtremez.com/" />

  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="180x180"
    href="{{ asset(setting('site_favicon', 'theme/xtremez/assets/icons/apple-touch-icon.png')) }}" />
  <link rel="stylesheet"
    href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
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

    @include('theme.xtremez.layouts.header')

    <main class="flex-fill">

      @yield('breadcrumb')

      @yield('content')


    </main>

    @include('theme.xtremez.layouts.footer')
  </div>

  <script>
    const appUrl = "{{ env('APP_URL') }}";
  </script>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <!-- Bootstrap 5 Bundle JS (includes Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Select2 -->
  <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>

  @stack('scripts')

  <!-- custom JS -->
  <script src="{{ asset('theme/xtremez/assets/js/main.js') }}"></script>
  <script src="{{ asset('theme/xtremez/assets/js/cart.js') }}"></script>
  <script src="{{ asset('assets/js/form.js') }}"></script>


</body>

</html>
