@extends('theme.xtremez.layouts.app')
@section('breadcrumb')
  <section class="breadcrumb-bar py-2">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 py-2">
          <li class="breadcrumb-item">
            <a href="{{ route('home') }}" class="text-white" title="Home">
              <!-- <i class="bi bi-house"></i> -->
              Home
            </a>
          </li>
          <li class="breadcrumb-item active text-white" aria-current="page" title="Sign In / Sign Up">
            Sign In / Sign Up
          </li>
        </ol>
      </nav>
    </div>
  </section>
@endsection
@section('content')
  <section class="heading-section py-5">
    <div class="container">
      <div class="heading-row">
        <h2 class="section-title fs-1 text-center m-0">Sign In / Sign
          Up</h2>
      </div>
    </div>
  </section>

  <section class="login-section pb-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-12 col-xl-10">
          <div class="row g-0 shadow login-boxes">
            <!-- Sign Up -->
            <div class="col-md-6 bg-white p-5 d-flex flex-column justify-content-center login-signup-box">
              <h3 class="mb-4 fw-bold" style="font-size: 2rem;">Sign
                Up</h3>
              <form action="{{ route('register') }}" method="POST" class="ajax-form">
                @csrf
                <div class="mb-3">
                  <input type="text" class="form-control theme-input" name="name" placeholder="Your Name"
                    autocomplete="off">
                </div>
                <div class="mb-3">
                  <input type="email" class="form-control theme-input" name="email" placeholder="Your email"
                    autocomplete="off">
                </div>
                <div class="mb-4">
                  <input type="password" class="form-control theme-input" name="password" placeholder="Password"
                    autocomplete="off">
                </div>
                <button type="submit" class="btn btn-secondary w-100">SIGN
                  UP</button>
              </form>
            </div>
            <!-- Sign In -->
            <div class="col-md-6 login-signin-box d-flex flex-column justify-content-center p-5">
              <h3 class="mb-4 fw-bold text-white" style="font-size: 2rem;">Sign In</h3>
              <form action="{{ route('login') }}" method="POST" class="ajax-form">
                @csrf
                <div class="mb-3">
                  <input type="text" name="email" class="form-control login-inp theme-input"
                    placeholder="Your Email">
                </div>
                <div class="mb-3">
                  <input type="password" name="password" class="form-control login-inp theme-input"
                    placeholder="Your Password">
                </div>
                <div class="pt-2 d-flex align-items-center justify-content-between">
                  <div>
                    <input type="checkbox" class="form-check-input me-2 theme-checkbox" id="rememberMe">
                    <label class="form-check-label text-white text-uppercase small" for="rememberMe" name="remember"
                      style="letter-spacing: 0.1em;">Remember
                      me</label>
                  </div>
                  <div>
                    <a class="text-white text-uppercase small" for="rememberMe" name="remember"
                      style="letter-spacing: 0.1em;">Forgot Password?</a>

                  </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-4">SIGN
                  IN</button>
              </form>

              @if (setting('social_login_enabled'))
                <span class="text-center small text-white mt-3">Or continue with</span>
                <div class="mt-3">
                  <div class="d-flex justify-content-center align-items-center gap-3">
                    @if (setting('social_login_github'))
                      {{-- GitHub --}}
                      <a href="{{ route('auth.provider.login', ['provider' => 'github']) }}"
                        class="rounded-circle d-inline-flex align-items-center justify-content-center"
                        style="width:44px;height:44px;background:#000;color:#fff" aria-label="Continue with GitHub"
                        title="Continue with GitHub">
                        <i class="bi bi-github fs-4"></i>
                      </a>
                    @endif

                    @if (setting('social_login_google'))
                      {{-- Google --}}
                      <a href="{{ route('auth.provider.login', ['provider' => 'google']) }}"
                        class="rounded-circle d-inline-flex align-items-center justify-content-center border"
                        style="width:44px;height:44px;background:#fff;color:#444" aria-label="Continue with Google"
                        title="Continue with Google">
                        <i class="bi bi-google fs-4"></i>
                      </a>
                    @endif

                    @if (setting('social_login_facebook'))
                      {{-- Facebook --}}
                      <a href="{{ route('auth.provider.login', ['provider' => 'facebook']) }}"
                        class="rounded-circle d-inline-flex align-items-center justify-content-center"
                        style="width:44px;height:44px;background:#1877F2;color:#fff" aria-label="Continue with Facebook"
                        title="Continue with Facebook">
                        <i class="bi bi-facebook fs-4"></i>
                      </a>
                    @endif
                    @if (setting('social_login_twitter'))
                      {{-- Twitter --}}
                      <a href="{{ route('auth.provider.login', ['provider' => 'twitter-oauth-2']) }}"
                        class="rounded-circle d-inline-flex align-items-center justify-content-center"
                        style="width:44px;height:44px;background:#000000;color:#fff" aria-label="Continue with X"
                        title="Continue with X">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                          class="bi bi-twitter-x" viewBox="0 0 16 16">
                          <path
                            d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z" />
                        </svg>
                      </a>
                    @endif
                  </div>
                </div>
              @endif


            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
