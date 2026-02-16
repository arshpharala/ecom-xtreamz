<label class="form-label mb-2">Pay With</label>

@foreach ($gateways as $gateway)
  @php
    $id = 'pay_' . $gateway->gateway;
    $label = ucfirst($gateway->additional['display_name'] ?? $gateway->gateway);
    $icons = [
        'stripe' => ['visa.png', 'mastercard.png', 'amex.png'],
        'paypal' => ['paypal.webp'],
        'razorpay' => ['razorpay.png'],
        'mashreq' => ['visa.png', 'mastercard.png', 'applepay.png'],
        'touras' => ['visa.png', 'mastercard.png'],
    ];
  @endphp

  <div class="py-1">
    <label class="form-check-label payment-method-box d-flex align-items-center w-100 mb-0 p-3" for="{{ $id }}"
      style="cursor:pointer;">
      <input class="form-check-input theme-radio me-3" type="radio" name="payment_method" value="{{ $gateway->gateway }}"
        id="{{ $id }}" {{ old('payment_method', 'stripe') === $gateway->gateway ? 'checked' : '' }}
        style="margin-left:0;">

      <span class="flex-grow-1">{{ $label }}</span>

      <span class="d-flex align-items-center gap-2">
        @foreach ($icons[$gateway->gateway] ?? [] as $icon)
          <img src="{{ asset('theme/xtremez/assets/icons/' . $icon) }}" alt="{{ $label }}" width="32">
        @endforeach
      </span>
    </label>
  </div>
@endforeach
