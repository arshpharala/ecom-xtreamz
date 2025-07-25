paypal
    .Buttons({
        createOrder: function (data, actions) {
            const form = $("#checkout-form");
            const formData = new FormData(form[0]);

            return $.ajax({
                url: "/paypal/create",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                data: formData,
                processData: false,
                contentType: false,
            })
                .then(function (response) {
                    return response.id;
                })
                .catch(function (xhr) {
                    handleValidationErrors(xhr, form);
                    throw new Error("Validation failed");
                });
        },

        onApprove: function (data, actions) {
            const form = $("#checkout-form");
            const captureData = new FormData();
            captureData.append("order_id", data.orderID);

            return $.ajax({
                url: "/paypal/capture",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                data: captureData,
                processData: false,
                contentType: false,
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
                    handlePaypalValidationErrors(xhr, form);
                });
        },
    })
    .render("#paypal-button-container");
