@extends('theme.medibazaar.layouts.app')
@section('content')
    <!-- hero-area start -->
    <section class="hero-area">
        <div class="hero-slider">
            <div class="slider-active">
                <div class="single-slider slider-height d-flex align-items-center"
                    data-background="{{ asset('theme/medibazaar/assets/img/slider/01.jpg') }}">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-5 col-lg-6">
                                <div class="hero-text mt-90">
                                    <div class="hero-slider-caption ">
                                        <span data-animation="fadeInUp" data-delay=".2s">covid -19 products</span>
                                        <h2 data-animation="fadeInUp" data-delay=".4s">Face Mask
                                            Thermometer</h2>
                                        <p data-animation="fadeInUp" data-delay=".6s">Quis autem vel eum iure reprehenin
                                            voluptate
                                            velit esse quam nihil molestiae conse</p>
                                    </div>
                                    <div class="hero-slider-btn">
                                        <a data-animation="fadeInUp" data-delay=".8s" href="contact.html"
                                            class="c-btn">shop now <i class="far fa-plus"></i></a>
                                        <div class="b-button" data-animation="fadeInUp" data-delay="1s"
                                            style="animation-delay: 1s;">
                                            <a href="contact.html">hot collection <i class="far fa-plus"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-7 col-lg-6">
                                <div class="slider-img d-none d-lg-block" data-animation="fadeInRight" data-delay=".8s">
                                    <img src="{{ asset('theme/medibazaar/assets/img/slider/01.png') }}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- hero-area end -->

    <!-- banner-area-start -->
    <div class="banner-area banner-pb pt-30 pb-70 pl-55 pr-55">
        <div class="container-fluid">
            <div class="row">
                @foreach ($bannerProducts as $variant)
                    <div class="{{ $loop->iteration > 3 ? 'col-xl-6 col-lg-6' : 'col-xl-4 col-lg-4' }}">
                        <div class="banner-wrapper mb-30">
                            <div class="banner-img pos-rel">
                                <a href="{{ $variant->link }}">
                                    <img src="{{ $variant->image }}" alt="">
                                </a>
                                <div class="banner-text">
                                    <span>{{ $variant->category_name }}</span>
                                    <h2>{{ $variant->name }}</h2>
                                    <div class="b-button red-b-button">
                                        <a href="{{ $variant->link }}">shop now <i class="far fa-plus"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- banner-area-end -->

    <!-- product-area-start -->
    <div class="product-area pb-50">
        <div class="container">
            <div class="tab-border mb-60">
                <div class="row">
                    <div class="col-xl-7 col-lg-6">
                        <div class="section-title mb-25">
                            <h2>Features Products</h2>
                            <p>Sed ut perspiciatis unde omnis iste natus error</p>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-6">
                        <div class="product-tab mb-25">
                            <ul class="nav justify-content-start justify-content-lg-end product-nav" id="myTab"
                                role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                        role="tab" aria-controls="home" aria-selected="true">
                                        <i class="far fa-shield-check"></i> Best Seller
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                                        aria-controls="profile" aria-selected="false">
                                        <i class="far fa-star"></i> Top Rated
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                                        aria-controls="contact" aria-selected="false">
                                        <i class="far fa-star"></i> Popular
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="product-tab-content">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">
                            @foreach ($bestSellerProducts as $variant)
                                <div class="col-xl-4 cl-lg-4 col-md-6">
                                    <div class="product-wrapper text-center mb-45">
                                        <div class="product-img pos-rel">
                                            <a href="product-details.html">
                                                <img src="{{ $variant->image }}" alt="">
                                                <img class="secondary-img" src="{{ $variant->image }}"
                                                    alt="{{ $variant->name }}">
                                            </a>
                                            <div class="product-action">
                                                <a class="action-btn" href="#"><i class="far fa-heart"></i></a>
                                                <a class="c-btn" href="#">add to cart <i
                                                        class="far fa-plus"></i></a>
                                                <a class="action-btn" href="#"><i class="far fa-search"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-text">
                                            <h5>{{ $variant->category_name }}</h5>
                                            <h4><a href="{{ $variant->link }}">{{ $variant->name }}</a></h4>
                                            <span>{!! $variant->price_with_currency !!}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="row">
                            @foreach ($topRatedProducts as $variant)
                                <div class="col-xl-4 cl-lg-4 col-md-6">
                                    <div class="product-wrapper text-center mb-45">
                                        <div class="product-img pos-rel">
                                            <a href="product-details.html">
                                                <img src="{{ $variant->image }}" alt="">
                                                <img class="secondary-img" src="{{ $variant->image }}"
                                                    alt="{{ $variant->name }}">
                                            </a>
                                            <div class="product-action">
                                                <a class="action-btn" href="#"><i class="far fa-heart"></i></a>
                                                <a class="c-btn" href="#">add to cart <i
                                                        class="far fa-plus"></i></a>
                                                <a class="action-btn" href="#"><i class="far fa-search"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-text">
                                            <h5>{{ $variant->category_name }}</h5>
                                            <h4><a href="{{ $variant->link }}">{{ $variant->name }}</a></h4>
                                            <span>{!! $variant->price_with_currency !!}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                        <div class="row">
                            @foreach ($popularProducts as $variant)
                                <div class="col-xl-4 cl-lg-4 col-md-6">
                                    <div class="product-wrapper text-center mb-45">
                                        <div class="product-img pos-rel">
                                            <a href="product-details.html">
                                                <img src="{{ $variant->image }}" alt="">
                                                <img class="secondary-img" src="{{ $variant->image }}"
                                                    alt="{{ $variant->name }}">
                                            </a>
                                            <div class="product-action">
                                                <a class="action-btn" href="#"><i class="far fa-heart"></i></a>
                                                <a class="c-btn" href="#">add to cart <i
                                                        class="far fa-plus"></i></a>
                                                <a class="action-btn" href="#"><i class="far fa-search"></i></a>
                                            </div>
                                        </div>
                                        <div class="product-text">
                                            <h5>{{ $variant->category_name }}</h5>
                                            <h4><a href="{{ $variant->link }}">{{ $variant->name }}</a></h4>
                                            <span>{!! $variant->price_with_currency !!}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- product-area-end -->

    @if (!empty($deal))
        <!-- deal-area-start -->
        <div class="deal-area pb-50 pt-95">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 offset-lg-3 offset-xl-3">
                        <div class="section-title text-center mb-65">
                            <h2>{{ $deal->translation->title }}</h2>
                            <p>{{ $deal->translation->description }}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-8 offset-lg-2 offset-xl-3">
                        <div class="deal-wrapper text-center">
                            <div class="deal-count">
                                <div class="deal-time" data-countdown="{{ $deal->ends_at }}"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- deal-area-end -->
    @endif

    <!-- banner-area-start -->
    <div class="banner-02-area pb-70 pl-165 pr-165">
        <div class="container-fluid">
            <div class="row">
                @foreach ($dealProducts as $variant)
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="banner-02-wrapper text-center mb-30" data-bg-color="#edf7fb">
                            <div class="banner-02-text">
                                <span>{{ $variant->category_name }}</span>
                                <h2>{{ $variant->name }}</h2>
                            </div>
                            <div class="banner-02-img pos-rel">
                                <a href="{{ $variant->link }}"><img src="{{ $variant->image }}"
                                        alt="{{ $variant->name }}"></a>
                                <span class="banner-tag">hot</span>
                            </div>
                            <div class="banner-price">
                                <span class="old-price">{{ $variant->price_with_currency }}</span>
                                <span
                                    class="new-price">{{ $variant->offer_data['discounted_price_with_currency'] }}</span>
                            </div>
                            <div class="banner-button">
                                <a class="c-btn" href="{{ $variant->link }}">buy now <i class="far fa-plus"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- banner-area-end -->

    <!-- product-area-start -->
    <div class="product-area pb-70">
        <div class="container">
            <div class="row mb-30">
                <div class="col-xl-7 col-lg-7 col-md-7">
                    <div class="section-title mb-30">
                        <h2>Features Products</h2>
                        <p>Sed ut perspiciatis unde omnis iste natus error</p>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-5 col-md-5">
                    <div class="b-button shop-btn s-btn text-md-right mb-30">
                        <a href="{{ route('products', ['is_featured' => 1]) }}">view all product <i
                                class="fal fa-long-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($featuredProducts as $variant)
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="product-02-wrapper pos-rel text-center mb-30">
                            <div class="product-02-img pos-rel">
                                <a href="{{ $variant->link }}">
                                    <img src="{{ $variant->image }}" alt="{{ $variant->name }}">
                                </a>
                                <div class="product-action">
                                    <a class="action-btn" href="#"><i class="far fa-heart"></i></a>
                                    <a class="action-btn" href="#"><i class="far fa-cart-plus"></i></a>
                                    <a class="action-btn" href="{{ $variant->link }}"><i class="far fa-eye"></i></a>
                                </div>
                            </div>
                            <div class="product-text">
                                <h5>{{ $variant->category_name }}</h5>
                                <h4><a href="{{ $variant->link }}">{{ $variant->name }}</a></h4>
                                <span>{{ $variant->price_with_currency }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- product-area-end -->

    <!-- testimonial-area-start -->
    <div class="testimonial-area pt-100 pb-175" data-background="{{ asset('theme/medibazaar/assets/img/bg/test.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 offset-lg-3 offset-xl-3">
                    <div class="section-title text-center mb-65">
                        <h2>What Our Client’s Say</h2>
                        <p>Sed ut perspiciatis unde omnis iste natus error</p>
                    </div>
                </div>
            </div>
            <div class="row test-active">
                @foreach ($testimonials as $testimonial)
                    <div class="col-xl-6">
                        <div class="testimonial-wrapper">
                            <div class="inner-test d-flex justify-content-between align-items-center">
                                <div class="test-img">
                                    <img src="{{ asset('storage/' . $testimonial->image) }}" alt="Testimonial">
                                </div>
                                <div class="test-rating">
                                    @for ($i = 0; $i < $testimonial->rating; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor

                                </div>
                            </div>
                            <div class="test-text">
                                <p>{{ $testimonial->translation->description }}</p>
                                <h4>{{ $testimonial->translation->name }}</h4>
                                <span>{{ $testimonial->designation }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- testimonial-area-end -->

    <!-- blog-area-start -->
    <div class="blog-area pt-105 pb-75">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 offset-lg-3 offset-xl-3">
                    <div class="section-title text-center mb-65">
                        <h2>Latest News & Blog</h2>
                        <p>Sed ut perspiciatis unde omnis iste natus error</p>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($news as $n)
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="blog-wrapper mb-30">
                            <div class="blog-img pos-rel">
                                <a href="{{ route('news.show', ['news' => $news->slug]) }}"><img
                                        src="{{ asset('theme/medibazaar/assets/img/blog/01.jpg') }}" alt=""></a>
                                <span class="blog-tag color-1">covid -19</span>
                            </div>
                            <div class="blog-text">
                                <div class="blog-meta">
                                    <span><i class="far fa-calendar-alt"></i> <a
                                            href="{{ route('news.show', ['news' => $news->slug]) }}">{{ $n->published_at->format('d f Y') }}</a></span>
                                </div>
                                <h4><a
                                        href="{{ route('news.show', ['news' => $news->slug]) }}">{{ $n->translation->title }}</a>
                                </h4>
                                <p>{{ $n->translation->intro }}</p>
                                <div class="b-button gray-b-button">
                                    <a href="{{ route('news.show', ['news' => $news->slug]) }}">read more <i
                                            class="far fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- blog-area-end -->

    <!-- brand-area-start -->
    <div class="brand-area pb-40">
        <div class="container">
            <div class="row">
                @foreach ($brands as $brand)
                    <div class="col-xl-2 col-lg-2 col-md-3 col-6">
                        <div class="single-brand mb-60">
                            <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- brand-area-end -->

    <!-- features-area-start -->
    <div class="features-area pt-60 pb-30 grey-2-bg">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="features-wrapper mb-30">
                        <div class="features-icon fe-1 f-left">
                            <i class="fal fa-ship"></i>
                        </div>
                        <div class="features-text">
                            <h3>Free Shipping</h3>
                            <p>Sed perspicia unde omnis iste
                                nat error voluptate accus</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="features-wrapper mb-30">
                        <div class="features-icon fe-2 f-left">
                            <i class="fal fa-usd-circle"></i>
                        </div>
                        <div class="features-text">
                            <h3>Money Back</h3>
                            <p>Sed perspicia unde omnis iste
                                nat error voluptate accus</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="features-wrapper mb-30">
                        <div class="features-icon fe-3 f-left">
                            <i class="fal fa-unlock-alt"></i>
                        </div>
                        <div class="features-text">
                            <h3>100% Secure</h3>
                            <p>Sed perspicia unde omnis iste
                                nat error voluptate accus</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- features-area-end -->

@endsection
