<div id="payment" class="profile-tab">
  <div class="profile-main bg-white p-4">
    <div class="row g-4 p-4">

      {{-- Saved Cards Section --}}
      <div class="col-md-12">
        <div class="fw-semibold mb-2">Saved Cards</div>

        @forelse ($user->paymentMethods() as $card)
          @php
            $brand = strtolower($card->card->brand);
            $last4 = $card->card->last4;
            $expMonth = str_pad($card->card->exp_month, 2, '0', STR_PAD_LEFT);
            $expYear = $card->card->exp_year;
            $isDefault = $user->defaultPaymentMethod()?->id === $card->id;
          @endphp

          <div class="card-list-box border d-flex align-items-center justify-content-between mb-4">
            <div class="card-list d-flex align-items-center gap-3 flex-grow-1 p-3">
              <img src="{{ asset('theme/xtremez/assets/icons/' . $brand . '.png') }}" alt="Card"
                style="width: 38px; height: 25px;">
              <span class="text-black-50">**** {{ $last4 }}</span>
              <div class="ms-5" style="letter-spacing: 1.2px;">
                <span class="text-black-50">EXPIRES <span
                    class="text-black">{{ $expMonth }}/{{ $expYear }}</span></span>
                @if ($isDefault)
                  <span class="badge bg-success ms-3">Default</span>
                @endif
              </div>
            </div>

            <div>
              <button type="button" class="btn btn-delete p-3"
                data-url="{{ route('customers.cart.delete', ['card' => $card->id]) }}">
                <i class="bi bi-trash"></i>
              </button>
            </div>
          </div>
        @empty
          <div class="text-muted">No saved cards.</div>
        @endforelse
      </div>

      {{-- Add New Card Form --}}
      <div class="col-md-8">
        <form id="card-form">
          <div class="fw-semibold mb-2">Add New Card</div>

          <div class="mb-3">
            <input type="text" class="form-control theme-input" id="cardholder-name" placeholder="Name on Card">
          </div>

          <div id="card-element" class="form-control theme-input p-3 mb-3" style="border: 1px solid #ccc;"></div>
          <div id="card-errors" class="text-danger mb-3"></div>

          <button type="button" id="save-card-btn" class="btn btn-save btn-secondary">SAVE CHANGES</button>
        </form>
      </div>

    </div>
  </div>
</div>

<script>
  window.initStripeCardForm = function() {

    
    const stripeKey = "{{ env('STRIPE_KEY') }}";
    const stripe = Stripe(stripeKey);
    const elements = stripe.elements();

    // ✅ Declare in outer scope
    let cardElement;

    if (!document.querySelector('#card-element .__PrivateStripeElement')) {
      cardElement = elements.create('card');
      cardElement.mount('#card-element');
    } else {
      // ✅ If already mounted, retrieve it again
      cardElement = elements.getElement('card');
    }

    const saveBtn = document.getElementById('save-card-btn');
    const cardholderName = document.getElementById('cardholder-name');
    const cardErrors = document.getElementById('card-errors');

    saveBtn.addEventListener('click', async () => {
      saveBtn.disabled = true;
      cardErrors.textContent = '';

      const {
        paymentMethod,
        error
      } = await stripe.createPaymentMethod({
        type: 'card',
        card: cardElement, // ✅ Will now be defined
        billing_details: {
          name: cardholderName.value
        }
      });

      if (error) {
        cardErrors.textContent = error.message;
        saveBtn.disabled = false;
        return;
      }

      fetch("{{ route('customers.card.store') }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({
            payment_method: paymentMethod.id
          })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            alert('Card saved successfully!');
            location.reload();
          } else {
            cardErrors.textContent = data.error || 'An error occurred.';
          }
        })
        .catch(() => {
          cardErrors.textContent = 'An unexpected error occurred.';
        })
        .finally(() => {
          saveBtn.disabled = false;
        });
    });
  }
</script>
