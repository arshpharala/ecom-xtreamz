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

        {{-- Billing Address --}}
        <div class="col-lg-6">
          <div class="fw-bold mb-3 section-label">Billing Address</div>
          <div class="bg-white p-4 shadow-sm">

            @if($addresses->isNotEmpty())
              <div class="mb-3 fw-bold">Select Saved Address</div>
              @foreach($addresses as $address)
              <div class="form-check mb-2">
                <input class="form-check-input saved-address" type="radio" name="saved_address_id" value="{{ $address->id }}" id="address_{{ $address->id }}">
                <label class="form-check-label w-100 ms-2" for="address_{{ $address->id }}">
                  {{ $address->name }}, {{ $address->address }}, {{ $address->area }}, {{ $address->city }}, {{ $address->province }}
                </label>
              </div>
              @endforeach
              <hr>
            @endif

            <div class="mb-2 fw-bold">Or Enter New Address</div>
            <div id="newAddressSection">
              <input type="text" name="name" class="form-control theme-input mb-2" placeholder="Full Name">
              <input type="text" name="phone" class="form-control theme-input mb-2" placeholder="Mobile Number">
              <input type="text" name="province" class="form-control theme-input mb-2" placeholder="Province">
              <input type="text" name="city" class="form-control theme-input mb-2" placeholder="City">
              <input type="text" name="area" class="form-control theme-input mb-2" placeholder="Area">
              <input type="text" name="address" class="form-control theme-input mb-2" placeholder="Street / Building / Flat">
              <textarea name="landmark" class="form-control theme-input mb-2" placeholder="Landmark (Optional)"></textarea>
            </div>
          </div>
        </div>

        {{-- Payment Section --}}
        <div class="col-lg-6">
          <div class="fw-bold mb-3 section-label">Payment Method</div>
          <div class="bg-white p-4 shadow-sm">

            @if($cards->isNotEmpty())
              <div class="mb-3 fw-bold">Select Saved Card</div>
              @foreach($cards as $card)
              <div class="form-check mb-2">
                <input class="form-check-input saved-card" type="radio" name="card_token" value="{{ $card->card_token }}" id="card_{{ $card->id }}">
                <label class="form-check-label ms-2 w-100" for="card_{{ $card->id }}">
                  **** **** **** {{ $card->card_last_four }} ({{ ucfirst($card->card_brand) }})
                </label>
              </div>
              @endforeach
              <hr>
            @endif

            <div class="mb-3">
              <label class="form-label">Pay With</label>
              <div>
                <label class="form-check-label d-block" for="payCard">
                  <input type="radio" name="payment_method" value="card" id="payCard" checked class="me-2">
                  Credit / Debit Card
                </label>
                <label class="form-check-label d-block" for="payPaypal">
                  <input type="radio" name="payment_method" value="paypal" id="payPaypal" class="me-2">
                  PayPal
                </label>
              </div>
            </div>

            <div id="cardSection">
              <input type="text" name="card_name" id="cardName" class="form-control theme-input mb-3" placeholder="Cardholder Name">
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

  const form = document.getElementById('loggedInCheckoutForm');
  const cardSection = document.getElementById('cardSection');

  // Disable new address fields if a saved address is selected
  document.querySelectorAll('.saved-address').forEach(radio => {
    radio.addEventListener('change', () => {
      document.querySelectorAll('#newAddressSection input, #newAddressSection textarea').forEach(field => {
        field.disabled = true;
        field.classList.add('bg-light');
      });
    });
  });

  // Disable Stripe fields if saved card selected
  document.querySelectorAll('.saved-card').forEach(radio => {
    radio.addEventListener('change', () => {
      cardSection.style.display = 'none';
    });
  });

  // Toggle card section based on payment method
  document.querySelectorAll('input[name="payment_method"]').forEach(el => {
    el.addEventListener('change', () => {
      const value = el.value;
      if (value === 'card') {
        cardSection.style.display = 'block';
      } else {
        cardSection.style.display = 'none';
      }
    });
  });

  form.addEventListener('submit', async function (e) {
    const usingSavedCard = document.querySelector('input[name="card_token"]:checked');
    const method = document.querySelector('input[name="payment_method"]:checked').value;

    if (method === 'paypal' || usingSavedCard) return;

    e.preventDefault();

    const cardholderName = document.getElementById('cardName').value;
    if (!cardholderName) {
      document.getElementById('card-errors').textContent = 'Cardholder name is required.';
      return;
    }

    const formData = new FormData(form);

    const response = await fetch(form.action, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      body: formData
    });

    const res = await response.json();
    if (!res.clientSecret) {
      alert('Something went wrong.');
      return;
    }

    const { error, paymentIntent } = await stripe.confirmCardPayment(res.clientSecret, {
      payment_method: {
        card: card,
        billing_details: { name: cardholderName }
      }
    });

    if (error) {
      document.getElementById('card-errors').textContent = error.message;
    } else if (paymentIntent.status === 'succeeded') {
    //   window.location.href = `/order-summary/${res.order_id}`;
    }
  });
</script>
@endpush
