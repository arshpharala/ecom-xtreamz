@extends('theme.xtremez.layouts.app')

@push('head')
  @foreach ($gateways as $gateway)
    @switch($gateway->gateway)
      @case('stripe')
        <meta name="stripe-key" content="{{ $gateway->key }}">
        <script src="https://js.stripe.com/v3/"></script>
      @break

      @case('paypal')
        @php
          $currency = $gateway->additional['currency'] ?? 'USD';
        @endphp
        <script src="https://www.paypal.com/sdk/js?client-id={{ $gateway->key }}&currency={{ $currency }}"></script>
      @break

      @case('razorpay')
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
      @break
    @endswitch
  @endforeach
@endpush

@section('breadcrumb')
  <section class="breadcrumb-bar py-2">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 py-2">
          <li class="breadcrumb-item">
            <a href="#" class="text-white" title="Home">
              <!-- <i class="bi bi-house"></i> -->
              Home
            </a>
          </li>
          <li class="breadcrumb-item">
            <a href="{{ route('cart.index') }}" class="text-white" title="My Cart">
              <!-- <i class="bi bi-house"></i> -->
              My Cart
            </a>
          </li>
          <li class="breadcrumb-item active text-white" aria-current="page" title="Checkout">
            Checkout
          </li>
        </ol>
      </nav>
    </div>
  </section>
@endsection

@section('content')
  <section class="heading-section py-5">
    <div class="container">
      <div class="heading-row position-relative">
        <div class="left-tools">
          <a href="{{ url()->previous() }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> <span class="d-none d-md-inline">BACK</span>
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

              @auth
                @if ($addresses->isNotEmpty())
                  <div class="mb-3 fw-bold">Select Saved Address</div>
                  <div class="mb-5">
                    @foreach ($addresses as $address)
                      <div class="default-address-box mb-1">
                        <div class="form-check ">
                          <input class="form-check-input theme-radio mt-4 saved-address" type="radio"
                            name="saved_address_id" value="{{ $address->id }}" id="address_{{ $address->id }}">
                          <label class="form-check-label w-100 ms-4" for="defaultAddress">
                            {!! $address->render() !!}
                          </label>
                        </div>
                      </div>
                    @endforeach
                  </div>
                @endif

                <div class="fw-semibold mb-2">Add New Address</div>

              @endauth

              @include('theme.xtremez.components.checkout._address-form', ['provinces' => $provinces])
            </div>
          </div>

          <!-- Payment Method -->
          <div class="col-lg-6">
            <div class="fw-bold mb-3 section-label">Payment Method</div>
            <div class="bg-white p-4 mb-4 shadow-sm">

              @auth
                @if ($cards->isNotEmpty())
                  <div class="fw-bold mb-3">Saved Payment Method</div>
                  @foreach ($cards as $card)
                    <div class="mb-5 saved-card-box">
                      <label class="form-label mb-2 ms-4">Card
                        Number</label>
                      <div class="form-check">
                        <input class="form-check-input theme-radio mt-4 saved-card" type="radio" name="card_token"
                          value="{{ $card->card_token }}" id="card_{{ $card->id }}">
                        <label class="form-check-label w-100 bg-white border p-3" for="card_{{ $card->id }}">
                          <div class="saved-card-detail d-flex align-items-center justify-content-between">
                            <span>**** **** **** {{ $card->card_last_four }}</span>
                            <img src="{{ asset('theme/xtremez/assets/icons/' . strtolower($card->card_brand) . '.png') }}"
                              alt="{{ ucfirst($card->card_brand) }}" width="38" />
                          </div>
                        </label>
                      </div>
                    </div>
                  @endforeach
                @endif
              @endauth

              <!-- Payment Type -->
              <div class="mb-4">
                @include('theme.xtremez.components.checkout._payment-method', ['gateways' => $gateways])

              </div>

              <div id="cardSection">
                @include('theme.xtremez.components.checkout._card-form')
              </div>

              <button type="submit" class="btn btn-secondary w-100 mt-3" id="place-order-button">PLACE ORDER</button>
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
