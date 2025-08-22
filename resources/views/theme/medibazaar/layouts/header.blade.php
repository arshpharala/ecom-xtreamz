        <!-- header-start -->
        <header>
            <div class="header-top-area pl-165 pr-165">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-8 col-lg-6 col-md-6">
                            <div class="header-top-wrapper">
                                <div class="header-top-info d-none d-xl-block f-left">
                                    <span><i class="fas fa-heart"></i> Welcome to Medibazae. We provides <a
                                            href="#">Covid-19 </a>medical accessories</span>
                                </div>
                                <div class="header-link f-left">
                                    <span><a href="tel:{!! setting('contact_phone') !!}"><i class="far fa-phone"></i>
                                            {!! setting('contact_phone') !!}</a></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6">
                            <div class="header-top-right text-md-right">
                                <div class="shop-menu">
                                    <ul>
                                        <li><a href="{{ route('customers.profile') }}"><i
                                                    class="fal fa-user-circle"></i> My Account</a></li>
                                        <li><a href="{{ route('cart.index') }}"><i class="far fa-cart-plus"></i>
                                                Cart</a></li>
                                        <li><a href="{{ route('customers.profile') }}#wishlist"><i
                                                    class="far fa-heart"></i> Wishlist</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="sticky-header" class="main-menu-area menu-01 pl-165 pr-165">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-xl-3 col-lg-3">
                            <div class="logo">
                                <a href="route('v2.home')">
                                    @if (setting('site_logo'))
                                        <img src="{{ asset(setting('site_logo')) }}" alt="Site Logo">
                                    @else
                                        <img src="{{ asset('theme/medibazaar/assets/img/logo/logo.png') }}"
                                            alt="Site Logo" />
                                    @endif
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-9 col-lg-9 d-none d-lg-block">
                            <div class="header-right f-right">
                                {{-- <div class="header-lang f-right pos-rel d-none d-md-none d-lg-block">
                                    <div class="lang-icon">
                                        <img src="{{ asset('theme/medibazaar/assets/img/icon/flag.png') }}"
                                            alt="">
                                        <a href="#"><i class="far fa-angle-down"></i></a>
                                    </div>
                                    <ul class="header-lang-list">
                                        <li><a href="#">USA</a></li>
                                        <li><a href="#">UK</a></li>
                                        <li><a href="#">CA</a></li>
                                        <li><a href="#">AU</a></li>
                                    </ul>
                                </div> --}}
                                <div class="menu-bar info-bar f-right d-none d-md-none d-lg-block">
                                    <a href="#"><i class="fal fa-bars"></i></a>
                                </div>
                                <div class="header-search f-right d-none d-xl-block">
                                    <form class="header-search-form">
                                        <input placeholder="Search" type="text">
                                        <button type="submit"><i class="far fa-search"></i></button>
                                    </form>
                                </div>
                            </div>
                            <div class="main-menu">
                                <nav id="mobile-menu">
                                    <ul>
                                        <li><a href="{{ route('v2.home') }}">Home</a>
                                            {{-- <ul class="sub-menu text-left">
                                                <li><a href="index.html">home 1</a></li>
                                                <li><a href="index-2.html">home 2</a></li>
                                                <li><a href="index-3.html">home 3</a></li>
                                            </ul> --}}
                                        </li>
                                        <li><a href="{{ route('v2.products') }}">Shop</a>
                                            {{-- <ul class="sub-menu text-left">
                                                <li><a href="shop-grid.html">shop grid</a>
                                                <li><a href="shop-full-width.html">shop full width</a>
                                                <li><a href="shop-04-column.html">shop 04 column</a>
                                                <li><a href="shop-left-02-column.html">shop left 02 column</a>
                                                <li><a href="shop-left-sidebar.html">shop left sidebar</a>
                                                <li><a href="shop-right-sidebar.html">shop right sidebar</a>
                                                <li><a href="product-details.html">product details</a>
                                                <li><a href="wishlist.html">wishlist</a>
                                                <li><a href="checkout.html">checkout</a>
                                                <li><a href="cart.html">cart</a>
                                                <li><a href="login.html">login</a>
                                                <li><a href="register.html">register</a>

                                            </ul> --}}
                                        </li>
                                        {{-- <li><a href="shop-grid.html">Categories </a></li> --}}
                                        <li><a href="{{ route('v2.blogs.index') }}">Blog</a>
                                            {{-- <ul class="sub-menu text-left">
                                                <li><a href="blog-grid.html">blog grid</a></li>
                                                <li><a href="blog-grid-sidebar.html">blog grid sidebar</a></li>
                                                <li><a href="blog-standard.html">blog standardr</a></li>
                                                <li><a href="blog-details.html">blog details</a></li>
                                            </ul> --}}
                                        </li>
                                        {{-- <li><a href="#">pages</a>
                                            <ul class="sub-menu text-left">
                                                <li><a href="about.html">about</a></li>
                                                <li><a href="contact.html">contact</a></li>
                                            </ul>
                                        </li> --}}
                                        <li><a href="{{ route('about-us') }}">About Us</a></li>
                                        <li><a href="{{ route('contact-us') }}">contact</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mobile-menu"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="extra-info">
                <div class="close-icon">
                    <button>
                        <i class="far fa-window-close"></i>
                    </button>
                </div>
                <div class="logo-side mb-30">
                    <a href="{{ route('v2.home') }}">
                        @if (setting('site_logo'))
                            <img src="{{ asset(setting('site_logo')) }}" alt="Site Logo">
                        @else
                            <img src="{{ asset('theme/medibazaar/assets/img/logo/white-logo.png') }}"
                                alt="Site Logo" />
                        @endif
                    </a>
                </div>
                <div class="side-info mb-30">
                    <div class="contact-list mb-30">
                        <h4>Office Address</h4>
                        <p>{!! setting('address') !!}</p>
                    </div>
                    <div class="contact-list mb-30">
                        <h4>Phone Number</h4>
                        <p>{!! setting('contact_phone') !!}</p>
                    </div>
                    <div class="contact-list mb-30">
                        <h4>Email Address</h4>
                        <p>{!! setting('contact_email') !!}</p>
                    </div>
                </div>
            </div>
            <div class="social-icon-right mt-20">
                @if (setting('facebook'))
                    <a href="{{ setting('facebook') }}">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                @endif
                @if (setting('twitter'))
                    <a href="{{ setting('twitter') }}">
                        <i class="fab fa-twitter-f"></i>
                    </a>
                @endif
                @if (setting('instagram'))
                    <a href="{{ setting('instagram') }}">
                        <i class="fab fa-instagram-f"></i>
                    </a>
                @endif
            </div>
            </div>
        </header>
        <!-- header-start -->
