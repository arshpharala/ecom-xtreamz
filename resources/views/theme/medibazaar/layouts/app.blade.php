<!doctype html>
<html class="no-js" lang="{{ locale()->code }}" style="direction:{{ locale()->direction ?? 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    {!! render_meta_tags($meta ?? null) !!}
    <link rel="manifest" href="site.webmanifest">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('theme/medibazaar/assets/img/favicon.ico') }}">
    <!-- Place favicon.ico in the root directory -->

    <!-- CSS here -->
    <!-- <link rel="stylesheet" href="{{ asset('theme/medibazaar/assets/css/bootstrap.min.css') }}"> -->
    <link rel="stylesheet" href="{{ asset('theme/medibazaar/assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/medibazaar/assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/medibazaar/assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/medibazaar/assets/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/medibazaar/assets/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/medibazaar/assets/css/meanmenu.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/medibazaar/assets/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/medibazaar/assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/medibazaar/assets/css/responsive.css') }}">

</head>

<body>
    @include('theme.medibazaar.layouts.header')
    <main>
        @yield('content')
    </main>
    @include('theme.medibazaar.layouts.footer')

    <!-- JS here -->
    <script src="{{ asset('theme/medibazaar/assets/js/vendor/modernizr-3.5.0.min.js') }}"></script>
    <script src="{{ asset('theme/medibazaar/assets/js/vendor/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('theme/medibazaar/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('theme/medibazaar/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('theme/medibazaar/assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('theme/medibazaar/assets/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('theme/medibazaar/assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('theme/medibazaar/assets/js/jquery.meanmenu.min.js') }}"></script>
    <script src="{{ asset('theme/medibazaar/assets/js/ajax-form.js') }}"></script>
    <script src="{{ asset('theme/medibazaar/assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('theme/medibazaar/assets/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('theme/medibazaar/assets/js/jquery.appear.js') }}"></script>
    <script src="{{ asset('theme/medibazaar/assets/js/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('theme/medibazaar/assets/js/jquery.knob.js') }}"></script>
    <script src="{{ asset('theme/medibazaar/assets/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('theme/medibazaar/assets/js/jquery.scrollUp.min.js') }}"></script>
    <script src="{{ asset('theme/medibazaar/assets/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('theme/medibazaar/assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('theme/medibazaar/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('theme/medibazaar/assets/js/main.js') }}"></script>

    @stack('scripts')
</body>

</html>
