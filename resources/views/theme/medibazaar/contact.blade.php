@extends('theme.medibazaar.layouts.app')
@section('content')
    <!-- breadcrumb-area-start -->
    <div class="breadcrumb-area pt-125 pb-125" style="background-image:url({{ asset('storage/' . $page->banner) }})">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="breadcrumb-wrapper">
                        <div class="breadcrumb-text">
                            <h2>contact</h2>
                        </div>
                        <ul class="breadcrumb-menu">
                            <li><a href="{{ route('home') }}">home</a></li>
                            <li><span>contact</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb-area-end -->

    <!-- contact-area-start -->
    <div class="contact-area pos-rel pt-100 pb-160">
        <div class="shape d-none d-xl-block">
            <div class="shape-item con-01"><img src="{!! page_content('main-section-icon', 'image') !!}" alt="contact"></div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-lg-4">
                    <div class="contact-address-wrapper mt-20 mb-30">
                        <div class="section-title mb-30">
                            <h2>{!! page_content('main-section', 'heading', 'Conatct Us') !!}</h2>
                            <p>{!! page_content('main-section', 'content', 'Sed ut perspiciatis unde omnis natus error') !!}</p>
                        </div>
                        <ul class="contact-address-link">
                            <li>
                                <div class="contact-address-icon f-left mr-25">
                                    <i class="far fa-map-marked-alt"></i>
                                </div>
                                <div class="contact-address-text">
                                    <span>Head Office</span>
                                    <h4>{!! setting('address') !!}</h4>
                                </div>
                            </li>
                            <li>
                                <div class="contact-address-icon f-left mr-25">
                                    <i class="far fa-phone"></i>
                                </div>
                                <div class="contact-address-text">
                                    <span>phone number</span>
                                    <h4>{!! setting('contact_phone') !!}</h4>
                                </div>
                            </li>
                            <li>
                                <div class="contact-address-icon f-left mr-25">
                                    <i class="far fa-envelope-open"></i>
                                </div>
                                <div class="contact-address-text">
                                    <span>email address</span>
                                    <h4>{!! setting('contact_email') !!}</h4>
                                </div>
                            </li>
                            <li>
                                <div class="contact-address-icon f-left mr-25">
                                    <i class="far fa-clock"></i>
                                </div>
                                <div class="contact-address-text">
                                    <span>Head Office</span>
                                    <h4>{!! setting('working_hours') !!}</h4>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-8">
                    <div class="contact-img mb-30">
                        <img src="{!! page_content('main-section', 'image') !!}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- contact-area-end -->

    <!-- map-area-start -->
    <div class="map-area">
        <div class="map-wrapper">
            {!! setting(
                'google_map',
                '<iframe
                                    src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d14602.254272231177!2d90.3654215!3d23.7985508!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sbd!4v1592201299593!5m2!1sen!2sbd"
                                    allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>',
            ) !!}

        </div>
    </div>
    <!-- map-area-end -->

    <!-- contact-area-start -->
    <div class="contact-area pt-100 pb-100">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 offset-lg-3 offset-xl-3">
                    <div class="section-title text-center mb-65">
                        <h2>{!! page_content('contact-form', 'heading', 'Send Us A Message') !!}</h2>
                        <p>{!! page_content('contact-form', 'content', 'Sed ut perspiciatis unde omnis iste natus error') !!}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <form id="contacts-form" class="contacts-form" action="#">
                        <div class="row">
                            <div class="col-lg-4 col-md-12">
                                <div class="contacts-icon contactss-name">
                                    <input type="text" placeholder="Your Full Name ">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12">
                                <div class="contacts-icon contactss-email">
                                    <input type="email" placeholder="Your Email Address">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12">
                                <div class="contacts-icon contactss-website">
                                    <input type="text" placeholder="Your Phone">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="contacts-icon contactss-message">
                                    <textarea name="comments" id="comments" cols="30" rows="10" placeholder="Your Comments...."></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="contacts-form-button text-center">
                                    <button class="c-btn">
                                        send message <i class="far fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- contact-area-end -->
@endsection
