<div id="touras-card-section" style="display: none;" class="mt-4 border-top pt-4">
  <div class="fw-semibold mb-3">Enter Card Details (Touras)</div>

  <div class="row">
    <div class="col-12 mb-3">
      <label class="form-label">Cardholder Name</label>
      <input type="text" name="card_name" class="form-control theme-input" placeholder="Name on Card">
    </div>

    <div class="col-12 mb-3">
      <label class="form-label">Card Number</label>
      <input type="text" name="card_no" class="form-control theme-input" placeholder="XXXX XXXX XXXX XXXX"
        maxlength="19">
    </div>

    <div class="col-md-4 mb-3">
      <label class="form-label">Expiry Month</label>
      <select name="exp_month" class="form-select theme-input">
        <option value="">Month</option>
        @for ($i = 1; $i <= 12; $i++)
          <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
        @endfor
      </select>
    </div>

    <div class="col-md-4 mb-3">
      <label class="form-label">Expiry Year</label>
      <select name="exp_year" class="form-select theme-input">
        <option value="">Year</option>
        @php $currentYear = date('Y'); @endphp
        @for ($i = $currentYear; $i <= $currentYear + 10; $i++)
          <option value="{{ $i }}">{{ $i }}</option>
        @endfor
      </select>
    </div>

    <div class="col-md-4 mb-3">
      <label class="form-label">CVV</label>
      <input type="password" name="cvv2" class="form-control theme-input" placeholder="123" maxlength="4">
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const tourasSection = document.getElementById('touras-card-section');

    function toggleTourasFields() {
      const selected = document.querySelector('input[name="payment_method"]:checked');
      if (selected && selected.value === 'touras') {
        tourasSection.style.display = 'block';
      } else {
        tourasSection.style.display = 'none';
      }
    }

    paymentRadios.forEach(radio => {
      radio.addEventListener('change', toggleTourasFields);
    });

    // Initial check
    toggleTourasFields();
  });
</script>
