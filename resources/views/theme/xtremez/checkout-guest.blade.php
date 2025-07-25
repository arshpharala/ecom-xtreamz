@extends('theme.xtremez.layouts.app')

@push('head')
  <meta name="stripe-key" content="{{ env('STRIPE_KEY') }}">
  <script src="https://www.paypal.com/sdk/js?client-id={{ env('PAYPAL_CLIENT_ID') }}&currency={{ active_currency() }}">
  </script>
  <script src="https://js.stripe.com/v3/"></script>
@endpush

@section('content')
  <section class="heading-section py-5">
    <div class="container">
      <div class="heading-row position-relative">
        <div class="left-tools">
          <a href="{{ url()->previous() }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>
            <span class="d-none d-md-inline">BACK</span>
          </a>
        </div>
        <h2 class="section-title fs-1 text-center m-0">Checkout</h2>
      </div>
    </div>
  </section>

  <section class="checkout-section pb-5">
    <div class="container">
      <form action="{{ route('checkout') }}" method="POST" id="checkout-form">
        @csrf
        <div class="row gx-4 gy-4">

          <div class="col-lg-6">
            <div class="fw-bold mb-3 section-label">Billing Address</div>
            <div class="bg-white p-4 shadow-sm">

              @include('theme.xtremez.components.checkout._address-form', [
                  'provinces' => $provinces,
              ])

            </div>
          </div>


          <div class="col-lg-6">
            <div class="fw-bold mb-3 section-label">Payment Method</div>
            <div class="bg-white p-4 shadow-sm">

              <div class="mb-3">
                @include('theme.xtremez.components.checkout._payment-method')
              </div>

              <div id="cardSection">
                @include('theme.xtremez.components.checkout._card-form')
              </div>

              <button class="btn btn-secondary w-100 mt-3" id="place-order-button">PLACE ORDER</button>
              <div id="paypal-button-container" class="mt-3" style="display:none;"></div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </section>
@endsection

@push('scripts')
  <script src="{{ asset('assets/js/checkout.js') }}"></script>
  <script src="{{ asset('assets/js/stripe.js') }}"></script>
  <script src="{{ asset('assets/js/paypal.js') }}"></script>
@endpush
