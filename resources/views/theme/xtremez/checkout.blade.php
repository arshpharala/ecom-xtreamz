@extends('theme.xtremez.layouts.app')

@push('head')
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endpush

@section('breadcrumb')
  <section class="breadcrumb-bar py-2">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 py-2">
          <li class="breadcrumb-item">
            <a href="#" class="text-white" title="Home">Home</a>
          </li>
          <li class="breadcrumb-item">
            <a href="{{ route('cart.index') }}" class="text-white" title="My Cart">My Cart</a>
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
          <a href="{{ url()->previous() }}" class="btn btn-secondary d-none d-md-flex">
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

        @if (request()->has('error'))
          <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Payment Error:</strong> {{ request()->query('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        <div class="row gx-4 gy-4">

          {{-- Billing Address --}}
          <div class="col-lg-6">
            <div class="fw-bold mb-3 section-label">Billing Address</div>

            <div class="bg-white p-4 shadow-sm">
              @php
                $hasSavedAddresses = auth()->check() && isset($addresses) && $addresses->isNotEmpty();
                $billingFormHasErrors =
                    $errors->has('name') ||
                    $errors->has('email') ||
                    $errors->has('phone') ||
                    $errors->has('province_id') ||
                    $errors->has('city_id') ||
                    $errors->has('address') ||
                    $errors->has('landmark');
                $billingHasOldInput =
                    filled(old('name')) ||
                    filled(old('email')) ||
                    filled(old('phone')) ||
                    filled(old('province_id')) ||
                    filled(old('city_id')) ||
                    filled(old('address')) ||
                    filled(old('landmark'));
                $showBillingForm = !$hasSavedAddresses || $billingFormHasErrors || $billingHasOldInput;
                $shippingSameAsBilling =
                    !filled(old('shipping_name')) &&
                    !filled(old('shipping_phone')) &&
                    !filled(old('shipping_province_id')) &&
                    !filled(old('shipping_city_id')) &&
                    !filled(old('shipping_address')) &&
                    !filled(old('shipping_landmark'));
              @endphp

              @auth
                @if ($addresses->isNotEmpty())
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="fw-bold">Select Saved Address</div>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="toggleBillingFormBtn">
                      {{ $showBillingForm ? 'Hide New Address' : 'Add New Address' }}
                    </button>
                  </div>

                  <div class="mb-4">
                    @foreach ($addresses as $address)
                      <div class="default-address-box mb-1">
                        <div class="form-check">
                          <input class="form-check-input theme-radio mt-4 saved-address" type="radio"
                            name="saved_address_id" value="{{ $address->id }}" id="address_{{ $address->id }}"
                            data-lat="{{ $address->map_latitude }}" data-lng="{{ $address->map_longitude }}"
                            {{ old('saved_address_id') == $address->id ? 'checked' : '' }}>
                          <label class="form-check-label w-100 ms-4" for="address_{{ $address->id }}">
                            {!! $address->render() !!}
                          </label>
                        </div>
                      </div>
                    @endforeach
                  </div>
                @endif
              @endauth

              <div id="billingNewAddressSection" class="{{ $showBillingForm ? '' : 'd-none' }}">
                @auth
                  @if ($addresses->isNotEmpty())
                    <div class="fw-semibold mb-2">Add New Address</div>
                  @endif
                @endauth
                @include('theme.xtremez.components.checkout._address-form', ['provinces' => $provinces])
              </div>



            </div>
          </div>

          {{-- Shipping Address & Payment Method --}}
          <div class="col-lg-6">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div class="fw-bold section-label mb-0">Shipping Address</div>
              <div class="form-check form-switch m-0">
                <input class="form-check-input" type="checkbox" role="switch" id="shipping_same_as_billing"
                  {{ $shippingSameAsBilling ? 'checked' : '' }}>
                <label class="form-check-label small" for="shipping_same_as_billing">Same as billing</label>
              </div>
            </div>

            <div class="bg-white p-4 mb-4 shadow-sm">
              <div id="shippingAddressSection" class="{{ $shippingSameAsBilling ? 'd-none' : '' }}">
                <div class="mb-3">
                  <label class="form-label">Full Name</label>
                  <input type="text" name="shipping_name" class="form-control theme-input"
                    placeholder="Enter shipping recipient name" value="{{ old('shipping_name') }}">
                </div>

                <div class="mb-3">
                  <label class="form-label">Mobile Number <small>({{ active_country()->phone_code }})</small></label>
                  <input type="text" name="shipping_phone" class="form-control theme-input"
                    placeholder="Enter shipping mobile number" value="{{ old('shipping_phone') }}">
                </div>

                <div class="mb-3">
                  <label class="form-label">Province</label>
                  <select name="shipping_province_id" id="shipping-province-select" class="form-select theme-select">
                    <option value="">Select shipping province</option>
                    @foreach ($provinces ?? [] as $province)
                      <option value="{{ $province->id }}"
                        {{ old('shipping_province_id') == $province->id ? 'selected' : '' }}>{{ $province->name }}
                      </option>
                    @endforeach
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label">City</label>
                  <select name="shipping_city_id" id="shipping-city-select" class="form-select theme-select"
                    data-old-city="{{ old('shipping_city_id') }}">
                    <option value="">Select shipping city</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label">Address</label>
                  <input type="text" name="shipping_address" class="form-control theme-input"
                    placeholder="House no / building / street / area" value="{{ old('shipping_address') }}">
                </div>

                <div class="mb-3">
                  <label class="form-label">Landmark (Optional)</label>
                  <textarea name="shipping_landmark" class="form-control theme-input" placeholder="E.g. beside train station">{{ old('shipping_landmark') }}</textarea>
                </div>
              </div>

              <div class="mt-4">
                <div class="fw-semibold mb-2">Shipping Pin Location (Required)</div>
                <div id="shipping-map-picker" style="height: 280px; border: 1px solid #e5e7eb; border-radius: 8px;">
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                  <small class="text-muted">Click on map to drop shipping pin.</small>
                  <button type="button" class="btn btn-sm btn-outline-secondary" id="use-current-location">Use Current
                    Location</button>
                </div>

                <input type="hidden" name="shipping_map_latitude" id="shipping_map_latitude"
                  value="{{ old('shipping_map_latitude') }}">
                <input type="hidden" name="shipping_map_longitude" id="shipping_map_longitude"
                  value="{{ old('shipping_map_longitude') }}">
                <input type="hidden" name="shipping_map_url" id="shipping_map_url"
                  value="{{ old('shipping_map_url') }}">
              </div>
            </div>

            <div class="fw-bold mb-3 section-label">Payment Method</div>

            <div class="bg-white p-4 mb-4 shadow-sm">

              @auth
                @if ($cards->isNotEmpty())
                  <div class="fw-bold mb-3">Saved Payment Method</div>

                  @foreach ($cards as $card)
                    <div class="mb-5 saved-card-box">
                      <label class="form-label mb-2 ms-4">Card Number</label>

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

              {{-- Payment Type selector --}}
              <div class="mb-4">
                @include('theme.xtremez.components.checkout._payment-method', ['gateways' => $gateways])
              </div>

              {{-- Stripe Card UI only --}}
              <div id="cardSection">
                @include('theme.xtremez.components.checkout._card-form')
              </div>

              {{-- Place Order button --}}
              <button type="submit" class="btn btn-secondary w-100 mt-3" id="place-order-button">
                PLACE ORDER
              </button>

              {{-- PayPal buttons --}}
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
@endpush
