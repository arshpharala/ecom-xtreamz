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
      <form action="{{ route('checkout.guest.process') }}" method="POST" id="guestCheckoutForm">
        @csrf
        <div class="row gx-4 gy-4">

          {{-- Billing Address --}}
          <div class="col-lg-6">
            <div class="fw-bold mb-3 section-label">Billing Address</div>
            <div class="bg-white p-4 shadow-sm">

              <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control theme-input" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control theme-input">
              </div>

              <div class="mb-3">
                <label class="form-label">Mobile Number</label>
                <input type="text" name="phone" class="form-control theme-input" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Province</label>
                <select name="province" class="form-select theme-select" required>
                  <option value="" selected disabled>Select your province</option>
                  <option>Dubai</option>
                  <option>Abu Dhabi</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">City</label>
                <select name="city" class="form-select theme-select" required>
                  <option value="" selected disabled>Select your city</option>
                  <option>Dubai</option>
                  <option>Ajman</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">Area</label>
                <select name="area" class="form-select theme-select" required>
                  <option value="" selected disabled>Select your area</option>
                  <option>International City</option>
                  <option>Business Bay</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control theme-input"
                  placeholder="House no / building / street / area" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Landmark (Optional)</label>
                <textarea name="landmark" class="form-control theme-input" placeholder="E.g. beside train station"></textarea>
              </div>

            </div>
          </div>

          {{-- Payment Method --}}
          <div class="col-lg-6">
            <div class="fw-bold mb-3 section-label">Payment Method</div>
            <div class="bg-white p-4 shadow-sm">

              <div class="mb-3">
                <label class="form-label">Pay With</label>
                <div class="py-1">
                  <label class="form-check-label payment-method-box d-flex align-items-center w-100 mb-0 p-3"
                    for="payCard" style="cursor:pointer;">
                    <input class="form-check-input theme-radio me-3" type="radio" name="payment_method" id="payCard"
                      value="card" checked>
                    <span class="flex-grow-1">Credit / Debit Card</span>
                    <span class="d-flex align-items-center gap-2">
                      <img src="{{ asset('theme/xtremez/assets/icons/visa.png') }}" width="32">
                      <img src="{{ asset('theme/xtremez/assets/icons/mastercard.png') }}" width="32">
                      <img src="{{ asset('theme/xtremez/assets/icons/amex.png') }}" width="32">
                    </span>
                  </label>
                </div>
                <div class="py-1">
                  <label class="form-check-label payment-method-box d-flex align-items-center w-100 mb-0 p-3"
                    for="payPaypal" style="cursor:pointer;">
                    <input class="form-check-input theme-radio me-3" type="radio" name="payment_method" id="payPaypal"
                      value="paypal">
                    <span class="flex-grow-1">PayPal</span>
                    <span class="d-flex align-items-center gap-2">
                      <img src="{{ asset('theme/xtremez/assets/icons/paypal.webp') }}" width="32">
                    </span>
                  </label>
                </div>
              </div>

              {{-- Card Fields --}}
              <div id="cardSection">
                <div class="mb-3">
                  <label class="form-label">Cardholder Name</label>
                  <input type="text" name="card_name" class="form-control theme-input" id="cardName">
                </div>

                <div class="mb-3">
                  <label class="form-label">Card Details</label>
                  <div id="card-element" class="form-control theme-input"></div>
                  <div id="card-errors" class="text-danger mt-2"></div>
                </div>
              </div>

              {{-- Submit --}}
              <button type="submit" class="btn btn-place-order btn-secondary w-100 mt-4">PLACE YOUR ORDER</button>
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

    const form = document.getElementById('guestCheckoutForm');
    const cardSection = document.getElementById('cardSection');

    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
      radio.addEventListener('change', () => {
        cardSection.style.display = (radio.value === 'card') ? 'block' : 'none';
      });
    });

    form.addEventListener('submit', async function(e) {
      const selected = document.querySelector('input[name="payment_method"]:checked').value;
      if (selected === 'paypal') return;

      e.preventDefault();

      const cardholderName = document.getElementById('cardName').value;
      if (!cardholderName.trim()) {
        document.getElementById('card-errors').textContent = 'Cardholder name is required.';
        return;
      }

      // Step 1: Submit form data (except stripe) via AJAX to get client_secret
      const formData = new FormData(form);
      const response = await fetch(form.action, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
      });

      const res = await response.json();
      if (!res.clientSecret) {
        alert('Something went wrong.');
        return;
      }

      // Step 2: Confirm the payment using Stripe JS
      const {
        paymentIntent,
        error
      } = await stripe.confirmCardPayment(res.clientSecret, {
        payment_method: {
          card: card,
          billing_details: {
            name: cardholderName
          }
        }
      });

      if (error) {
        document.getElementById('card-errors').textContent = error.message;
      } else if (paymentIntent.status === 'succeeded') {
        // Redirect to thank you or confirm page
        window.location.href = `/order-summary/${res.order_id}`;
      }
    });
  </script>
@endpush
