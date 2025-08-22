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
            My Cart
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

  <section class="profile-section pb-5">
    <div class="container">
      <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
          <nav class="profile-sidebar d-flex flex-column gap-3">
            <a href="#" class="profile-link active" data-tab="profile" data-heading="My Profile">MY
              PROFILE</a>
            <a href="#" class="profile-link" data-tab="password" data-heading="Change Password">CHANGE
              PASSWORD</a>
            <a href="#" class="profile-link" data-tab="address" data-heading="Address Book">ADDRESS
              BOOK</a>
            <a href="#" class="profile-link" data-tab="payment" data-heading="My Payment Options">MY PAYMENT
              OPTIONS</a>
            <a href="#" class="profile-link" data-tab="order" data-heading="My Order History">MY ORDER
              HISTORY</a>
            <a href="#" class="profile-link" data-tab="wishlist" data-heading="Wishlist">WISHLIST</a>
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
  <script src="https://js.stripe.com/v3/"></script>
  <script src="{{ asset('theme/xtremez/assets/js/profile.js') }}"></script>
@endpush
