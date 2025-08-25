<!-- footer-area-start -->
<footer>
    <div class="footer-area pt-80 pb-45">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="footer-wrapper mb-30">
                        <h3 class="footer-title">About Company</h3>
                        <div class="footer-text">
                            <p>{{ setting('site_intro') }} </p>
                        </div>
                        <div class="footer-icon">
                            @if (setting('facebook'))
                                <a href="{{ setting('facebook') }}"><i class="fab fa-facebook-f"></i></a>
                            @endif

                            @if (setting('twitter'))
                                <a href="{{ setting('twitter') }}"><i class="fab fa-twitter"></i></a>
                            @endif

                            @if (setting('instagram'))
                                <a href="{{ setting('instagram') }}"><i class="fab fa-instagram"></i></a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="footer-wrapper ml-80 mb-30">
                        <h3 class="footer-title">Useful Links</h3>
                        <div class="footer-link">
                            <ul>
                                <li><a href="{{ route('customers.profile') }}#wishlist">My Wishlist</a></li>
                                <li><a href="{{ route('customers.profile') }}">My Account</a></li>
                                <li><a href="{{ route('v2.policy') }}">Return Policy</a></li>
                                <li><a href="{{ route('v2.policy') }}">Privacy Policy</a></li>
                                <li><a href="{{ route('v2.contact-us') }}">Contact Us</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="footer-wrapper ml-80 mb-30">
                        <h3 class="footer-title">Product</h3>
                        <div class="footer-link">
                            <ul>
                                @foreach (footer_categories(5) as $category)
                                    <li><a href="about.html">{{ $category->translation->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="footer-wrapper ml-130 mb-30">
                        <h3 class="footer-title">Payment</h3>
                        <div class="footer-img">
                            <img src="{{ asset('theme/medibazaar/assets/img/footer/footer.png') }}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom-area mr-70 ml-70 pt-25 pb-25">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <div class="copyright">
                        <p>{!! setting('copyright') !!}</p>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <div class="footer-bottom-link f-right">
                        <ul>
                            <li><a href="{{ route('v2.home') }}">Home </a></li>
                            <li><a href="{{ route('v2.about-us') }}"> About Us</a></li>
                            <li><a href="{{ route('v2.products') }}">Our Product</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- footer-area-end -->
