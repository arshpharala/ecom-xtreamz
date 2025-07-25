<label class="form-label mb-2">Pay With</label>

<div class="py-1">
  <label class="form-check-label payment-method-box d-flex align-items-center w-100 mb-0 p-3" for="payCard"
    style="cursor:pointer;">
    <input class="form-check-input theme-radio me-3" type="radio" name="payment_method" value="card" id="payCard"
      checked style="margin-left:0;">
    <span class="flex-grow-1">Credit / Debit Card</span>
    <span class="d-flex align-items-center gap-2">
      <img src="{{ asset('theme/xtremez/assets/icons/visa.png') }}" alt="Visa" width="32">
      <img src="{{ asset('theme/xtremez/assets/icons/mastercard.png') }}" alt="Mastercard" width="32">
      <img src="{{ asset('theme/xtremez/assets/icons/amex.png') }}" alt="Amex" width="32">
    </span>
  </label>

</div>

<div class="py-1">
  <label class="form-check-label payment-method-box d-flex align-items-center w-100 mb-0 p-3"
    for="payPaypal"cursor:pointer;">
    <input class="form-check-input theme-radio me-3" type="radio" name="payment_method" value="paypal" id="payPaypal"
      style="margin-left:0;">
    <span class="flex-grow-1">Paypal</span>
    <span class="d-flex align-items-center gap-2">
      <img src="{{ asset('theme/xtremez/assets/icons/paypal.webp') }}" alt="PayPal" width="32">
    </span>
  </label>

</div>
