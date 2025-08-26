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
          <li class="breadcrumb-item">
            <a href="{{ route('login') }}" class="text-white" title="Login">
              <!-- <i class="bi bi-house"></i> -->
              Login
            </a>
          </li>
          <li class="breadcrumb-item active text-white" aria-current="page" title="Forgot Password">
            Forgot Password
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
        <h2 class="section-title fs-1 text-center m-0">Forgot Password</h2>
      </div>
    </div>
  </section>

  <section class="login-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6">
          <div class="row g-0 shadow login-boxes">
            <!-- Sign Up -->
            <div class="col-md-12 bg-white p-5 d-flex flex-column justify-content-center login-signup-box">
              <p class="mb-4">Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
              <form action="{{ route('password.email') }}" method="POST" class="ajax-form">
                @csrf
                <div class="mb-3">
                  <input type="email" class="form-control theme-input" name="email" placeholder="Your email address" required>
                </div>
                <button type="submit" class="btn btn-secondary w-100">Send Password Reset Link</button>
              </form>
              <p class="mt-4 text-center"><a href="{{ route('login') }}" class="text-secondary"><i class="bi bi-arrow-left-circle me-1"></i> Back to Login</a></p>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
