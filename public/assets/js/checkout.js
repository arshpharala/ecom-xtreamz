$(document).ready(function () {
  const $cardSection = $("#cardSection");
  const $paypalContainer = $("#paypal-button-container");
  const $placeOrderBtn = $("#place-order-button");
  const $form = $("#checkout-form");

  $cardSection.hide();
  $paypalContainer.hide();
  $placeOrderBtn.hide();

  $('input[name="payment_method"]').on("change", function () {
    const value = $(this).val();

    if (value === "stripe") {
      $cardSection.show();
      $paypalContainer.hide();
      $placeOrderBtn.show();
    } else if (value === "paypal") {
      $cardSection.hide();
      $paypalContainer.show();
      $placeOrderBtn.hide();
    } else if (value === "touras") {
      // Touras popup will collect payment details
      $cardSection.hide();
      $paypalContainer.hide();
      $placeOrderBtn.show();
    } else if (value === "mashreq" || value === "cod") {
      $cardSection.hide();
      $paypalContainer.hide();
      $placeOrderBtn.show();
    } else {
      $cardSection.hide();
      $paypalContainer.hide();
      $placeOrderBtn.show();
    }
  });

  const selected = $('input[name="payment_method"]:checked').val();
  if (selected) $('input[name="payment_method"]:checked').trigger('change');

  $form.on("submit", async function (e) {
    if ($form.data("processing")) return;

    const paymentMethod = $('input[name="payment_method"]:checked').val();
    if (paymentMethod === 'paypal') return;

    e.preventDefault();
    $form.data("processing", true);

    const idbImages = await window.IDB.getAll();
    const formData = new FormData(this);

    if (idbImages && idbImages.length) {
      idbImages.forEach(item => {
        if (item.files && item.files.length) {
          Array.from(item.files).forEach(file => {
            formData.append(`customization_files[${item.id}][]`, file);
          });
        }
      });
    }

    $.ajax({
      url: $form.attr("action"),
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {



        if (response.redirect) {
          window.location.href = response.redirect;
          return;
        }

        if (response.clientSecret) {
          if (typeof confirmStripePayment === 'function') {
            confirmStripePayment(response.clientSecret, response.order_id);
          } else {
            console.error("confirmStripePayment not found. Check stripe.js exposure.");
          }
          return;
        }

        console.log("Checkout response:", response);
        $form.data("processing", false);
      },
      error: function (xhr) {
        $form.data("processing", false);
        alert("Order failed: " + (xhr.responseJSON?.message || "Unknown error"));
      }
    });
  });
});
