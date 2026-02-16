<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Processing Payment - Touras</title>

  {{-- IMPORTANT: jQuery + CryptoJS BEFORE Touras --}}
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>

  {{-- Touras JS --}}
  <script src="https://uatcheckout.tourasuae.com/ms-transaction-core-1-0/jscheckout/js-checkoutNewCheck.js"></script>

  <link href="https://uatcheckout.tourasuae.com/ms-transaction-core-1-0/jscheckout/resourcesJS/css/checkout.css"
    rel="stylesheet" type="text/css" />
  <link href="https://uatcheckout.tourasuae.com/ms-transaction-core-1-0/jscheckout/resourcesJS/css/swiper-bundle.min.css"
    rel="stylesheet" type="text/css" />

  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      background: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .loader-container {
      text-align: center;
      background: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, .08);
      width: min(520px, calc(100% - 32px));
    }

    .spinner {
      border: 4px solid #f3f3f3;
      border-top: 4px solid #3498db;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      animation: spin 1s linear infinite;
      margin: 0 auto 20px;
      display: block;
    }

    @keyframes spin {
      0% {
        transform: rotate(0)
      }

      100% {
        transform: rotate(360deg)
      }
    }

    .message {
      color: #333;
      font-size: 18px;
      font-weight: 600;
      margin-bottom: 10px;
    }

    .sub-message {
      color: #777;
      font-size: 14px;
    }

    #payNowBtn {
      margin-top: 20px;
      padding: 10px 24px;
      background: #3498db;
      color: #fff;
      border: 0;
      border-radius: 6px;
      cursor: pointer;
      display: none;
    }

    #payNowBtn[disabled] {
      opacity: .7;
      cursor: not-allowed;
    }
  </style>

  <script>
    const jsData     = @json($jsData);
    let spObj        = null;
    let payStarted   = false;

    document.addEventListener('transactionEvent', (event) => {
      const response = event?.detail;
      console.log("Received transaction event:", response);
      if (response) {
        handleTourasResponse(response);

      } else {
        console.warn("Transaction event received with no details.");
      }
    });


    function setMessage(text, spinning = true) {
      const spinner = document.querySelector('.spinner');
      const msg = document.querySelector('.message');
      msg.innerText = text;
      spinner.style.display = spinning ? 'block' : 'none';
    }


    function initTouras() {
      setMessage("Securely connecting to Touras...", true);

      try {
        if (typeof JsCheckout === 'undefined') {
          throw new Error("JsCheckout constructor not found (Touras JS not loaded).");
        }

        spObj = new JsCheckout();

        const options = {
          merchantId: jsData.merchantId,
          internalKey: jsData.internalKey
        };

        spObj.Init(options);

        // show manual button
        document.querySelector('#payNowBtn').style.display = 'inline-block';

        // Auto-submit after a short delay
        setTimeout(() => {
          Buy();
        }, 600);
      } catch (e) {
        console.error("Touras initialization failed:", e);
        setMessage("Touras init failed. Refresh and try again.", false);
      }
    }

    function CallbackForResponse(response) {
      console.log("ResponseInCallback:::", response);
    }

    function Buy() {
      if (!spObj) {
        alert("Touras not initialized yet. Please wait.");
        return;
      }
      if (payStarted) return;

      payStarted = true;
      document.querySelector('#payNowBtn').disabled = true;

      setMessage("Opening secure payment...", true);

      const orderDetailsJson = JSON.stringify(jsData.orderDetails);

      try {
        spObj.Pay(
          jsData.payMode,
          jsData.encryptedData,
          jsData.hash,
          orderDetailsJson,
          CallbackForResponse
        );
      } catch (e) {
        console.error("Touras Pay() error:", e);
        payStarted = false;
        document.querySelector('#payNowBtn').disabled = false;
        setMessage("Unable to start payment. Please try again.", false);
      }
    }

    function handleTourasResponse(rawOrArray) {
      if (!rawOrArray) return;

      const dataToSend = Array.isArray(rawOrArray) ? rawOrArray.join('|') : rawOrArray;

      setMessage("Verifying payment...", true);

      $.post("{{ route('touras.return') }}", {
          _token: "{{ csrf_token() }}",
          data: dataToSend
        })
        .done(function(res) {
          if (res && res.redirect) {
            window.location.href = res.redirect;
            return;
          }
          console.log("Return response:", res);
          alert("Payment verified. Please check your order status.");
        })
        .fail(function(xhr) {
          console.error("Verification failed:", xhr.status, xhr.responseText);
          alert("Verification failed. Please check the order status in your profile.");
          payStarted = false;
          document.querySelector('#payNowBtn').disabled = false;
          setMessage("Ready to open secure payment popup.", false);
        });
    }

    window.addEventListener('load', initTouras);
  </script>
</head>

<body>
  <div class="loader-container">
    <div class="spinner"></div>
    <div class="message">Securely connecting to Touras...</div>
    <div class="sub-message">
      Processing Order No: {{ $order->reference_number }}
    </div>

    <button id="payNowBtn" type="button" onclick="Buy()">Pay Now</button>
  </div>
</body>

</html>
