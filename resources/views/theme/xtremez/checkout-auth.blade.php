@extends('theme.xtremez.layouts.app')

@push('head')
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
      <form id="loggedInCheckoutForm" method="POST" action="{{ route('checkout.process') }}">
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
              <div id="newAddressSection">
                <div class="mb-3">
                  <label class="form-label">Full
                    Name</label>
                  <input type="text" name="name" class="form-control theme-input" placeholder="Enter your name">
                </div>

                <div class="mb-3">
                  <label class="form-label">Mobile
                    Number</label>
                  <input type="text" name="phone" class="form-control theme-input"
                    placeholder="Enter your mobile no">
                </div>

                <div class="mb-3">
                  <label class="form-label">Province</label>
                  <select name="province_id" id="province-select" class="form-select theme-select">
                    <option value="">Select your province</option>
                    @foreach ($provinces ?? [] as $province)
                      <option value="{{ $province->id }}">{{ $province->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label">City</label>
                  <select name="city_id" id="city-select" class="form-select theme-select">
                    <option value="">Select your city</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label">Area</label>
                  <select name="area_id" id="area-select" class="form-select theme-select">
                    <option value="">Select your area</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label">Address</label>
                  <input type="text" name="address" class="form-control theme-input"
                    placeholder="House no / building / street / area">
                </div>

                <div class="mb-3">
                  <label class="form-label">Landmark (Optional)</label>
                  <textarea name="landmark" class="form-control theme-input" placeholder="E.g. beside train station"></textarea>
                </div>

              </div>
            </div>
          </div>

          <!-- Payment Method -->
          <div class="col-lg-6">
            <div class="fw-bold mb-3 section-label">Payment
              Method</div>
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
                <label class="form-label mb-2">Pay With</label>

                <div class="py-1">
                  <label class="form-check-label payment-method-box d-flex align-items-center w-100 mb-0 p-3"
                    for="payCard" style="cursor:pointer;">
                    <input class="form-check-input theme-radio me-3" type="radio" name="payment_method" value="card"
                      id="payCard" checked style="margin-left:0;">
                    <span class="flex-grow-1">Credit
                      / Debit
                      Card</span>
                    <span class="d-flex align-items-center gap-2">
                      <img src="{{ asset('theme/xtremez/assets/icons/visa.png') }}" alt="Visa" width="32">
                      <img src="{{ asset('theme/xtremez/assets/icons/mastercard.png') }}" alt="Mastercard"
                        width="32">
                      <img src="{{ asset('theme/xtremez/assets/icons/amex.png') }}" alt="Amex" width="32">
                    </span>
                  </label>

                </div>

                <div class="py-1">
                  <label class="form-check-label payment-method-box d-flex align-items-center w-100 mb-0 p-3"
                    for="payPaypal"cursor:pointer;">
                    <input class="form-check-input theme-radio me-3" type="radio" name="payment_method"
                      value="paypal" id="payPaypal" style="margin-left:0;">
                    <span class="flex-grow-1">Paypal</span>
                    <span class="d-flex align-items-center gap-2">
                      <img src="{{ asset('theme/xtremez/assets/icons/paypal.webp') }}" alt="PayPal" width="32">
                    </span>
                  </label>

                </div>


              </div>

              <div id="cardSection">
                <div class="fw-semibold mb-2">Add new Card</div>

                <input type="text" name="card_name" id="cardName" class="form-control theme-input mb-3"
                  placeholder="Cardholder Name">
                <div id="card-element" class="form-control theme-input mb-2"></div>
                <div id="card-errors" class="text-danger small mt-2"></div>
              </div>

              <button class="btn btn-secondary w-100 mt-3">PLACE ORDER</button>
            </div>
          </div>

        </div>
      </form>
    </div>
  </section>
@endsection

@push('scripts')
<script>
  const stripe = Stripe("{{ env('STRIPE_KEY') }}");
  const elements = stripe.elements();
  const card = elements.create('card');
  card.mount('#card-element');

  $(document).ready(function () {
    const $form = $('#loggedInCheckoutForm');
    const $cardSection = $('#cardSection');

    // Disable new address fields if a saved address is selected
    $('.saved-address').on('change', function () {
      $('#newAddressSection').find('input, textarea').each(function () {
        $(this).prop('disabled', true).addClass('bg-light');
      });
    });

    // Disable Stripe fields if saved card is selected
    $('.saved-card').on('change', function () {
      $cardSection.hide();
    });

    // Toggle card section based on payment method
    $('input[name="payment_method"]').on('change', function () {
      const value = $(this).val();
      if (value === 'card') {
        $cardSection.show();
      } else {
        $cardSection.hide();
      }
    });

    // Form submission
    $form.on('submit', function (e) {
      const usingSavedCard = $('input[name="card_token"]:checked').length > 0;
      const selectedMethod = $('input[name="payment_method"]:checked').val();

      if (selectedMethod === 'paypal' || usingSavedCard) return; // Let native form submit

      e.preventDefault();

      const cardholderName = $('#cardName').val();
      if (!cardholderName) {
        $('#card-errors').text('Cardholder name is required.');
        return;
      }

      const formData = new FormData(this);

      $.ajax({
        url: $form.attr('action'),
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        data: formData,
        processData: false,
        contentType: false,
        success: async function (res) {
          if (!res.clientSecret) {
            alert('Something went wrong with the server response.');
            return;
          }

          const result = await stripe.confirmCardPayment(res.clientSecret, {
            payment_method: {
              card: card,
              billing_details: {
                name: cardholderName
              }
            }
          });

          if (result.error) {
            $('#card-errors').text(result.error.message);
          } else if (result.paymentIntent.status === 'succeeded') {
            window.location.href = `/order-summary/${res.order_number}`;
          }
        },
        error: function (xhr) {
          const msg = xhr.responseJSON?.message || 'Payment failed. Please try again.';
          $('#card-errors').text(msg);
        }
      });
    });
  });
</script>

@endpush
