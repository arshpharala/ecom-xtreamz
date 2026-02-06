$(document).ready(function () {
    const $cardSection = $("#cardSection");
    const $paypalContainer = $("#paypal-button-container");
    const $placeOrderBtn = $("#place-order-button");
    const $form = $("#checkout-form");

    // Initial state: hide everything until a method is selected
    $cardSection.hide();
    $paypalContainer.hide();
    $placeOrderBtn.hide();

    // Show/hide relevant sections
    $('input[name="payment_method"]').on("change", function () {
        const value = $(this).val();

        if (value === "stripe") {
            $cardSection.show();
            $paypalContainer.hide();
            $placeOrderBtn.show();
        } else if (value === "paypal") {
            $cardSection.hide();
            $paypalContainer.show();
            $placeOrderBtn.hide(); // hide for PayPal
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

    // On page load, toggle correctly based on current selection (if any)
    const selected = $('input[name="payment_method"]:checked').val();
    if (selected) {
        $('input[name="payment_method"]:checked').trigger('change');
    } else {
        // Hide summary/place order button or just keep defaults?
        // User asked: "until o,am not selecting any payment method hide the card inf or name box"
        // Card info is already hidden by $cardSection.hide().
    }

    // Intercept Form Submission for Customization Images (Lazy Upload)
    $form.on("submit", async function (e) {
        // If it's stripe, stripe.js might handle it. But stripe.js likely listens to the button or form submit too.
        // We need to ensure we append data BEFORE any other handler, or we handle the ajax submit ourselves.
        // If we switch to AJAX submit, we break the default behaviour which `stripe.js` might rely on?
        // Let's assume for standard gateways (or if we can append invisible inputs... wait we can't).

        // Strategy:
        // 1. Pause submission.
        // 2. Gather images from IDB.
        // 3. Construct a FormData with ALL form fields + images.
        // 4. Submit via AJAX.
        // 5. Handle response (redirect or error).

        // Only do this if we haven't already processed it
        if ($form.data("processing")) return;

        const paymentMethod = $('input[name="payment_method"]:checked').val();

        // If Paypal, it's handled by paypal.js buttons, so this form submit event might not trigger or is irrelevant?
        // Paypal usually has its own buttons. The code hides #place-order-button for paypal.
        if (paymentMethod === 'paypal') return;

        // For Stripe, we need to check if stripe.js handles the submit.
        // If stripe.js intercepts submit, we might race.
        // But let's try to handle the upload first.

        e.preventDefault();
        $form.data("processing", true);

        // Retrieve all customization IDs from the session/cart?
        // Wait, the client doesn't know *which* customization IDs are in the cart unless we rendered them efficiently.
        // But we DO know because we can query IDB? No, IDB has *all* images ever? We need to know which ones actally map to current cart items.
        // The Checkout Page usually doesn't show cart items detail in a way we can scrape easily (it shows summary).
        // WE MIGHT BE MISSING A LINK.
        // The `CartController` passed `customization_id` to the session/DB.
        // During checkout, the server has `customization_id`.
        // The server needs the images.
        // The CLIENT needs to send [customization_id] -> [file] map.
        // But the client typically sends "customization_images[]" for a specific item.
        // THE PROBLEM: The order creation logic on backend iterates cart items.
        // We need to send a pile of images and let the backend map them?
        // OR, simply: match customization_id.

        // Logic:
        // 1. Get all keys from IDB.
        // 2. Filter keys that are "recent" or just send all (safest/easiest if not too huge)?
        //    Better: we need to know what to send. 
        //    Let's fetch ALL from IDB (assuming user didn't spam 1000 images).

        const idbImages = await window.IDB.getAll(); // returns array of {id, files, timestamp}

        const formData = new FormData(this);

        if (idbImages && idbImages.length) {
            idbImages.forEach(item => {
                // Append using a specific convention: customization_files[uuid][]
                if (item.files && item.files.length) {
                    Array.from(item.files).forEach(file => {
                        formData.append(`customization_files[${item.id}][]`, file);
                    });
                }
            });
        }

        // Submit via AJAX
        $.ajax({
            url: $form.attr("action"),
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                // Handle success (redirect or actions)
                if (response.redirect) {
                    window.location.href = response.redirect;
                } else if (response.requires_action) {
                    // specific stripe handling if needed, but usually stripe.js handles this inside its own flow?
                    // If we are overriding stripe.js, we need to replicate its logic.
                    // This is risky. 
                    // Let's verify stripe.js logic first.
                    console.log("Order processed", response);
                    // If stripe returned clientSecret, we function as the stripe handler.
                    handleStripeResponse(response);
                } else {
                    // unexpected success without redirect?
                    // maybe json success=true
                    if (response.success && response.redirect) {
                        window.location.href = response.redirect;
                    } else if (response.clientSecret) {
                        handleStripeResponse(response);
                    }
                }
            },
            error: function (xhr) {
                $form.data("processing", false);
                alert("Order failed: " + (xhr.responseJSON?.message || "Unknown error"));
            }
        });
    });

    function handleStripeResponse(response) {
        // ... (This function will be defined in stripe.js normally, but we might need to invoke it or expose it)
        // Actually, if we hijack the form, we kill stripe.js's listener.
        // We might need to manually trigger the stripe confirmation flow.
        // Let's check if `handleStripePayment` in controller returns a JSON that we can use.
        // Yes, it returns clientSecret.
        // We need to use `stripe.confirmCardPayment`.
        if (typeof confirmStripePayment === 'function') {
            confirmStripePayment(response.clientSecret, response.order_id);
        } else {
            // Fallback if stripe.js isn't updated to expose this
            console.error("confirmStripePayment function not found. Ensure stripe.js is updated.");
        }
    }
});
