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
              @include('theme.xtremez.components.checkout._address-form', ['provinces' => $provinces])
            </div>
          </div>

          <!-- Payment Method -->
          <div class="col-lg-6">
            <div class="fw-bold mb-3 section-label">Payment Method</div>
            <div class="bg-white p-4 mb-4 shadow-sm">

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

              <!-- Payment Type -->
              <div class="mb-4">
                @include('theme.xtremez.components.checkout._payment-method')

              </div>

              <div id="cardSection">
                @include('theme.xtremez.components.checkout._card-form')
              </div>

              <button class="btn btn-secondary w-100 mt-3" id="place-order-button">PLACE ORDER</button>
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
