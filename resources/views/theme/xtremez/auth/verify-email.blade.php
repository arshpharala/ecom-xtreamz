@extends('theme.xtremez.layouts.app')

@section('breadcrumb')
  <section class="breadcrumb-bar py-2">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 py-2">
          <li class="breadcrumb-item">
            <a href="{{ route('home') }}" class="text-white" title="Home">Home</a>
          </li>
          <li class="breadcrumb-item active text-white" aria-current="page" title="Verify Email">
            Verify Email
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
        <h2 class="section-title fs-1 text-center m-0">Verify Your Email</h2>
      </div>
    </div>
  </section>

  <section class="login-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6">
          <div class="row g-0 shadow login-boxes">
            <div class="col-md-12 bg-white p-5 d-flex flex-column justify-content-center login-signup-box text-center">

              <div class="mb-4">
                <i class="bi bi-envelope-check fs-1 text-secondary"></i>
              </div>

              <h4 class="mb-3">Verify Your Email Address</h4>

              <p class="text-muted mb-4">
                Thanks for signing up! Please verify your email address by clicking on the link we just sent to your
                inbox.
              </p>

              @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success" role="alert">
                  A new verification link has been sent to your email address.
                </div>
              @endif

              <form method="POST" action="{{ route('verification.send') }}" class="ajax-form">
                @csrf
                <button type="submit" class="btn btn-primary w-100 mb-3">
                  Resend Verification Email
                </button>
              </form>

              <p class="mt-3 mb-0">
                <a href="{{ route('logout') }}"
                  onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                  class="text-secondary">
                  <i class="bi bi-arrow-left-circle me-1"></i> Back to Login
                </a>
              </p>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
