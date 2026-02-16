window.TourasCheckout = (function () {
  let tourasObj = null;

  function initIfNeeded(merchantId) {
    if (!tourasObj) {
      if (typeof JsCheckout === 'undefined') {
        console.error("Touras JsCheckout lib not loaded.");
        return;
      }
      tourasObj = new JsCheckout();
      tourasObj.Init({
        merchantId: merchantId
      });
    }
  }

  function pay(payload) {
    initIfNeeded(payload.merchantId);

    const paymode = payload.paymode || 'WEB';

    try {
      tourasObj.Pay(
        paymode,
        payload.encryptedData,
        payload.hash,
        JSON.stringify(payload.orderDetails),
        callback
      );
    } catch (e) {
      console.error("Touras Pay error:", e);
      alert("Touras payment failed to start: " + e.message);
    }
  }

  function callback(response) {
    console.log("Touras callback:", response);

    $.ajax({
      url: "/payment/touras/return",
      method: "POST",
      data: {
        _token: $('meta[name="csrf-token"]').attr('content'),
        data: response?.data || null,
        encData: response?.encData || null,
        hashData: response?.hashData || null
      },
      success: function (res) {
        if (res.redirect) {
          window.location.href = res.redirect;
        } else {
          alert("Payment processed but no redirect returned.");
        }
      },
      error: function (xhr) {
        console.log(xhr);
        alert("Touras verification failed on server.");
      }
    });
  }

  // Optional event listener (Touras docs)
  document.addEventListener('transactionEvent', (event) => {
    console.log("transactionEvent:", event?.detail);
  });

  return {
    pay
  };
})();