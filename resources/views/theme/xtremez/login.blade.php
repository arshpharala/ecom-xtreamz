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
                  <input type="text" class="form-control theme-input" name="name" placeholder="Your Name" autocomplete="off">
                </div>
                <div class="mb-3">
                  <input type="email" class="form-control theme-input" name="email" placeholder="Your email" autocomplete="off">
                </div>
                <div class="mb-4">
                  <input type="password" class="form-control theme-input" name="password" placeholder="Password" autocomplete="off">
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
                  <input type="text" name="email" class="form-control login-inp theme-input" placeholder="Your Name">
                </div>
                <div class="mb-3">
                  <input type="password" name="password" class="form-control login-inp theme-input" placeholder="Your Password">
                </div>
                <div class="py-2 d-flex align-items-center">
                  <input type="checkbox" class="form-check-input me-2 theme-checkbox" id="rememberMe">
                  <label class="form-check-label text-white text-uppercase small" for="rememberMe" name="remember"
                    style="letter-spacing: 0.1em;">Remember
                    me</label>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-5">SIGN
                  IN</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
