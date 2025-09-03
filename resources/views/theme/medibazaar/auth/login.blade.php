@extends('theme.medibazaar.layouts.app')
@section('content')


        <!-- breadcrumb-area-start -->
        <div class="breadcrumb-area pt-125 pb-125" style="background-image:url({{ asset('storage/' . $page->banner) }})">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="breadcrumb-wrapper">
                            <div class="breadcrumb-text">
                                <h2>login</h2>
                            </div>
                            {{-- <ul class="breadcrumb-menu">
                                <li><a href="index.html">home</a></li>
                                <li><span>login</span></li>
                            </ul> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- breadcrumb-area-end -->

        <!-- login Area Strat-->
        <section class="login-area pt-100 pb-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <div class="basic-login">
                            <h3 class="text-center mb-60">Login From Here</h3>
                            <form action="#">
                                <label for="name">Email Address <span>**</span></label>
                                <input id="name" type="text" placeholder="Enter Username or Email address..." />
                                <label for="pass">Password <span>**</span></label>
                                <input id="pass" type="password" placeholder="Enter password..." />
                                <div class="login-action mb-20 fix">
                                    <span class="log-rem f-left">
                                        <input id="remember" type="checkbox" />
                                        <label for="remember">Remember me!</label>
                                    </span>
                                    <span class="forgot-login f-right">
                                        <a href="#">Lost your password?</a>
                                    </span>
                                </div>
                                <button class="c-btn theme-btn-2 w-100">Login Now</button>
                                <div class="or-divide"><span>or</span></div>
                                <button class="c-btn theme-btn w-100">Register Now</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- login Area End-->


@endsection
