@extends('theme.medibazaar.layouts.app')
@section('content')
    <!-- breadcrumb-area-start -->
    <div class="breadcrumb-area pt-125 pb-125" style="background-image:url({{ asset('storage/' . $page->banner) }})">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="breadcrumb-wrapper">
                        <div class="breadcrumb-text">
                            <h2>About Us</h2>
                        </div>
                        <ul class="breadcrumb-menu">
                            <li><a href="{{ route('home') }}">home</a></li>
                            <li><span>About Us</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb-area-end -->

    <!-- about-area-start -->
    <div class="about-area about-pb pt-150 pb-70">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6">
                    <div class="about-img pos-rel mb-30">
                        <img src="{!! page_content('what-client-says', 'image') !!}" alt="about">
                        <div class="about-tag">
                            <h2>{!! page_content('what-client-says-year', 'heading', 25) !!}</h2>
                            <span>{!! page_content('what-client-says-year', 'content') !!}</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6">
                    <div class="about-wrapper pos-rel mb-30">
                        <div class="section-title mb-40">
                            <h2>{!! page_content('what-client-says', 'heading', 'What Our Client’s Say') !!}</h2>
                            <p>{!! page_content('what-client-says', 'content', 'Sed ut perspiciatis unde omnis iste natus error') !!}</p>
                        </div>
                        <div class="about-item">
                            <ul>
                                <li>
                                    <div class="about-text">
                                        <h4><i class="far fa-check-circle"></i>{!! page_content('what-client-says-1', 'heading', 'Our MIssion & Vision') !!}</h4>
                                        <p>{!! page_content('what-client-says-1', 'content', '') !!}</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="about-text">
                                        <h4><i class="far fa-check-circle"></i>{!! page_content('what-client-says-2', 'heading', 'Treatment For Covid -19') !!}</h4>
                                        <p>{!! page_content('what-client-says-2', 'content', '') !!}</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="about-button mt-45">
                            <a href="about.html" class="c-btn">meet with doctors <i class="far fa-plus"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- about-area-end -->

    <!-- counter-area-start -->
    <div class="counter-area pb-70">
        <div class="container">
            <div class="counter-bg pt-100">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-6">
                        <div class="counter-wrapper text-center mb-30">
                            <div class="counter-icon">
                                <img src="{!! page_content('stats-1', 'image') !!}" style="height: 50px;width:60px;" alt="stats">
                            </div>
                            <div class="counter-text">
                                <h2 class="counter">{!! page_content('stats-1', 'heading', 2560) !!}</h2>
                                <span>{!! page_content('stats-1', 'content', 'Saticfied Clients') !!}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6">
                        <div class="counter-wrapper text-center mb-30">
                            <div class="counter-icon">
                                <img src="{!! page_content('stats-2', 'image') !!}" style="height: 50px;width:60px;" alt="stats">
                            </div>
                            <div class="counter-text">
                                <h2 class="counter">{!! page_content('stats-2', 'heading', 2560) !!}</h2>
                                <span>{!! page_content('stats-2', 'content', 'Saticfied Clients') !!}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6">
                        <div class="counter-wrapper text-center mb-30">
                            <div class="counter-icon">
                                <img src="{!! page_content('stats-3', 'image') !!}" style="height: 50px;width:60px;" alt="stats">
                            </div>
                            <div class="counter-text">
                                <h2 class="counter">{!! page_content('stats-3', 'heading', 2560) !!}</h2>
                                <span>{!! page_content('stats-3', 'content', 'Saticfied Clients') !!}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-6">
                        <div class="counter-wrapper text-center mb-30">
                            <div class="counter-icon">
                                <img src="{!! page_content('stats-4', 'image') !!}" style="height: 50px;width:60px;" alt="stats">
                            </div>
                            <div class="counter-text">
                                <h2 class="counter">{!! page_content('stats-4', 'heading', 2560) !!}</h2>
                                <span>{!! page_content('stats-4', 'content', 'Saticfied Clients') !!}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- counter-area-end -->

    <!-- features-area-start -->
    <div class="features-02-area pt-100 pb-70">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 offset-lg-3 offset-xl-3">
                    <div class="section-title text-center mb-65">
                        <h2>{!! page_content('goals', 'heading', 'Our Main Goals') !!}</h2>
                        <p>{!! page_content('goals', 'content', 'Sed ut perspiciatis unde omnis iste natus error') !!}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="feature-02-wrapper text-center mb-30">
                        <div class="p-feature-text">
                            <h3>{!! page_content('goals-1', 'heading', 'Medical Accessories') !!}</h3>
                            <div class="feature-02-icon">
                                <img src="{!! page_content('goals-1', 'image') !!}" alt="Goal">
                            </div>
                            <p>{!! page_content('goals-1', 'content', 'Sed ut perspiciatis unde omnis
                                wste natsit volupta explic') !!}</p>
                            <a href="#"><i class="fal fa-long-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="feature-02-wrapper text-center mb-30">
                        <div class="p-feature-text">
                            <h3>{!! page_content('goals-2', 'heading', 'Covid - 19 Treatment Center') !!}</h3>
                            <div class="feature-02-icon">
                                <img src="{!! page_content('goals-2', 'image') !!}" alt="Goal">
                            </div>
                            <p>{!! page_content('goals-2', 'content', 'Sed ut perspiciatis unde omnis
                                wste natsit volupta explic') !!}</p>
                            <a href="#"><i class="fal fa-long-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="feature-02-wrapper text-center mb-30">
                        <div class="p-feature-text">
                            <h3>{!! page_content('goals-3', 'heading', '24/7 Hours Emergency Care') !!}</h3>
                            <div class="feature-02-icon">
                                <img src="{!! page_content('goals-3', 'image') !!}" alt="Goal">
                            </div>
                            <p>{!! page_content('goals-3', 'content', 'Sed ut perspiciatis unde omnis
                                wste natsit volupta explic') !!}</p>
                            <a href="#"><i class="fal fa-long-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="feature-02-wrapper text-center mb-30">
                        <div class="p-feature-text">
                            <h3>{!! page_content('goals-4', 'heading', 'Online Free Consultations') !!}</h3>
                            <div class="feature-02-icon">
                                <img src="{!! page_content('goals-4', 'image') !!}" alt="Goal">
                            </div>
                            <p>{!! page_content('goals-4', 'content', 'Sed ut perspiciatis unde omnis
                                wste natsit volupta explic') !!}</p>
                            <a href="#"><i class="fal fa-long-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- features-area-end -->
@endsection
