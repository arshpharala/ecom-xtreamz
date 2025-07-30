paypal
    .Buttons({
        /**
         * Step 1: Create Order (calls your backend to get PayPal Order ID)
         */
        createOrder: function (data, actions) {
            const form = $("#checkout-form");
            const formData = new FormData(form[0]);

            return $.ajax({
                url: `${appUrl}/paypal/create`,
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            })
                .then(function (response) {
                    if (!response.id) {
                        throw new Error("No order ID returned");
                    }
                    return response.id;
                })
                .catch(function (xhr) {
                    handleValidationErrors(xhr, form);
                    throw new Error("Order creation failed");
                });
        },

        /**
         * Step 2: On Approval (capture the order in backend)
         */
        onApprove: function (data, actions) {
            const form = $("#checkout-form");
            const captureData = new FormData();
            captureData.append("order_id", data.orderID);

            return $.ajax({
                url: `${appUrl}/paypal/capture`,
                method: "POST",
                data: captureData,
                processData: false,
                contentType: false,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            })
                .then(function (response) {
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Payment Failed",
                            text: "PayPal payment could not be completed.",
                        });
                    }
                })
                .catch(function (xhr) {
                    handleValidationErrors(xhr, form);
                });
        },

        /**
         * Step 3: Handle cancellation (optional)
         */
        onCancel: function (data) {
            Swal.fire({
                icon: "info",
                title: "Payment Cancelled",
                text: "You cancelled the PayPal payment.",
            });
        },

        /**
         * Step 4: Handle error (optional)
         */
        onError: function (err) {
            console.error("PayPal error:", err);
            Swal.fire({
                icon: "error",
                title: "PayPal Error",
                text: "Something went wrong while processing your payment.",
            });
        },
    })
    .render("#paypal-button-container");
