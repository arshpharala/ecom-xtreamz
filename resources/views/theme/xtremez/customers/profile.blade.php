@extends('theme.xtremez.layouts.app')

@section('banner')
  <section class="breadcrumb-bar py-2">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 py-2">
          <li class="breadcrumb-item">
            <a href="index.html" class="text-white" title="Home">
              <!-- <i class="bi bi-house"></i> -->
              Home
            </a>
          </li>
          <li class="breadcrumb-item active text-white" aria-current="page" title="Search">
            My Profile
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
        <h2 class="section-title fs-1 text-center m-0">My Profile</h2>
      </div>
    </div>
  </section>

  @if (is_null(auth()->user()->email_verified_at))
    <section class="profile-section pb-5" id="verifySection">
      <div class="container text-center">
        <div class="alert alert-info rounded-0" role="alert">
          <h4 class="alert-heading">Email Verification Required</h4>
          <p>Please verify your email address to access your profile features.</p>
          <hr>
          <p class="mb-3">
            Check your inbox for a verification email.
          </p>

          <form method="POST" action="{{ route('verification.send') }}" class="d-inline-block ajax-form">
            @csrf
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-envelope-check me-1"></i> Resend Verification Email
            </button>
          </form>
        </div>
      </div>
    </section>
  @endif


  <section class="profile-section pb-5">
    <div class="container">
      <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
          <nav class="profile-sidebar d-flex flex-column">
            <a href="#" class="profile-link border-bottom active" data-tab="profile" data-heading="My Profile">MY
              PROFILE</a>
            <a href="#" class="profile-link border-bottom" data-tab="password" data-heading="Change Password">CHANGE
              PASSWORD</a>
            <a href="#" class="profile-link border-bottom" data-tab="address" data-heading="Address Book">ADDRESS
              BOOK</a>
            <a href="#" class="profile-link border-bottom" data-tab="payment" data-heading="My Payment Options">MY
              PAYMENT
              OPTIONS</a>
            <a href="#" class="profile-link border-bottom" data-tab="order" data-heading="My Order History">MY ORDER
              HISTORY</a>

            <a href="#" class="profile-link border-bottom" data-tab="wishlist" data-heading="Wishlist">WISHLIST</a>

            <a href="#" class="profile-link"
              onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              LOGOUT
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>

          </nav>

          <!-- Mobile Accordion Container -->
          <div id="mobileProfileAccordion" class="accordion d-lg-none mt-3"></div>

        </div>

        <!-- Main Content -->
        <div class="col-lg-9" id="profileContent">



        </div>

      </div>
    </div>
  </section>
@endsection

@push('scripts')
  <script>
    setInterval(() => {
      fetch("{{ route('session.check') }}", {
          cache: "no-store"
        })
        .then(r => {
          if (!r.ok) location.reload();
        })
        .catch(() => location.reload());
    }, 100000);
  </script>

  <script src="https://js.stripe.com/v3/"></script>
  <script src="{{ asset('theme/xtremez/assets/js/profile.js') }}"></script>
@endpush
