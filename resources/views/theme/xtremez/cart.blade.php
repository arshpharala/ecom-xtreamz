@extends('theme.xtremez.layouts.app')
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
          <li class="breadcrumb-item active text-white" aria-current="page" title="My Cart">
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
      <div class="heading-row position-relative">
        <h2 class="section-title fs-1 text-center m-0">My Cart</h2>
      </div>
    </div>
  </section>

  <!-- cart section -->
  <section class="cart-section pb-5">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="cart-selectall d-flex align-items-center mb-3 d-none d-md-flex">
            <input type="checkbox" id="selectAll" class="cc-form-check-input form-check-input me-2" />
            <label for="selectAll" class="form-label mb-0">Select
              all</label>
          </div>
        </div>
      </div>
      <div class="row gx-4 gy-4">
        <!-- Cart List -->
        <div class="col-md-12 col-lg-7 col-xl-8">
          <div class="cart-list bg-grey p-4">
            @if ($variants->isNotEmpty())
              <div class="cart-items">
                <!-- Cart Item Start -->
                @foreach ($variants as $variant)
                  <div class="cart-item row gx-2 gy-2 align-items-center border-bottom pb-3 mb-3"
                    data-variant-id="{{ $variant->variant_id }}" data-price="{{ $variant->price }}"
                    data-qty="{{ $variant->qty }}">
                    <div class="col-auto d-none d-md-flex align-items-center">
                      <input type="checkbox" class="cc-form-check-input form-check-input me-2" />
                    </div>

                    <div class="col-4 col-md-4 cart-img-box">
                      <img src="{{ asset($variant->image) }}" class="cart-img" alt="{{ $variant->name }}" />
                    </div>

                    <div class="col-9 col-md-6 cart-product-title d-flex align-self-start">
                      <span class="fw-bold m-4">
                        {{ $variant->name }}
                      </span>
                    </div>

                    <div class="align-items-end col-md col-xl d-md-flex d-none flex-column">
                      <div class="qty-delete-box d-flex flex-row gap-2">
                        <div class="cart-qty-box d-flex flex-column">
                          <div class="cart-qty-val text-center">{{ $variant->qty }}</div>
                          <button class="btn btn-trash"><i class="bi bi-trash"></i></button>
                        </div>
                        <div class="d-flex cart-qty-box flex-column">
                          <button class="btn qty-btn plus border border-1"><i class="bi bi-plus"></i></button>
                          <button class="btn qty-btn minus border border-1"><i class="bi bi-dash"></i></button>
                        </div>
                      </div>
                    </div>

                    <div class="col-12 d-flex d-md-none mt-2">
                      <div class="qty-delete-box-mobile w-100 d-flex justify-content-between align-items-center">
                        <div class="cart-qty-box d-flex align-items-center">
                          <button class="btn qty-btn minus"><i class="bi bi-dash"></i></button>
                          <div class="cart-qty-val text-center">{{ $variant->qty }}</div>
                          <button class="btn qty-btn plus"><i class="bi bi-plus"></i></button>
                        </div>
                        <button class="btn btn-trash ms-2"><i class="bi bi-trash"></i></button>
                      </div>
                    </div>
                  </div>
                @endforeach

              </div>
            @else
              <div class="d-flex justify-content-around align-middle" style="min-height: 400px">
                <img src="{{ asset('assets/images/empty-cart.png') }}" class="w-50" alt="Empty Cart">
              </div>
            @endif
          </div>
        </div>
        <!-- Cart Summary -->
        <div class="col-md-12 col-lg-5 col-xl-4">
          <div class="cart-summary bg-grey p-4">
            <ul class="list-unstyled mb-4">
              <li class="d-flex justify-content-between mb-2">
                <span>Subtotal</span>
                <span class="cart-sub-total">
                    {!! price_format(active_currency(), $cart['subTotal']) !!}</span>
              </li>
              <li class="d-flex justify-content-between mb-2">
                <span>Taxes</span>
                <span class="cart-taxes">
                    {!! price_format(active_currency(), $cart['tax']) !!}
                </span>
              </li>
              @if (session('applied_coupon'))
                <li class="d-flex justify-content-between mb-2 align-items-center">
                  <div>
                    <span>Coupon ({{ session('applied_coupon.code') }})</span>
                    <a href="#" class="text-danger p-0 ms-2 remove-coupon">
                      <i class="bi bi-x-circle"></i> Remove
                    </a>
                  </div>
                  <span class="text-success">
                    -{!! price_format(active_currency(), session('applied_coupon.discount')) !!}
                  </span>
                </li>
              @endif

              <li class="d-flex justify-content-between fw-bold border-top pt-2">
                <span>Total</span>
                <span class="cart-total text-black">{!! price_format(active_currency(), $cart['total']) !!}</span>
                {{-- <span class="cart-total text-black">{!! number_format($cart['total'], 2) !!} {!! active_currency(true)->symbol !!}</span> --}}
              </li>
            </ul>


            <form class="cc-form w-100 py-4">
              <div class="input-group border border-black">
                <input type="text" class="form-control border-0" placeholder="Gift card number" />
                <span class="input-group-text border-0 bg-grey pe-2">
                  <button class="btn btn-dark cc-button btn-apply" type="button">Apply</button>
                </span>
              </div>
            </form>
            <a class="btn btn-secondary btn-checkout w-100" href="{{ route('checkout') }}">
              CHECKOUT <img src="{{ 'theme/xtremez/assets/icons/right-arrow.png' }}" alt="arrow-icon" class="ms-1">
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
