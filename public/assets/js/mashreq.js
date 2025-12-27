$(document).ready(function () {
    $("#checkout-form").on("submit", function (e) {
        const method = $('input[name="payment_method"]:checked').val();
        if (method !== "mashreq") return;

        e.preventDefault();

        const form = $(this);
        const btn = $("#place-order-button");

        btn.prop("disabled", true).text("Processing...");

        $.ajax({
            url: form.attr("action"),
            method: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },

            success: function (res) {
                if (!res.session_id) {
                    alert("Unable to start Mashreq payment");
                    btn.prop("disabled", false).text("PLACE ORDER");
                    return;
                }

                /**
                 * ==========================
                 * MOCK MODE (NO MPGS CALL)
                 * ==========================
                 */
                if (res.session_id.startsWith("MOCK_")) {
                    // Directly simulate successful payment
                    window.location.href = `/mashreq/return?order.id=${res.order_id}`;

                    return;
                }

                /**
                 * ==========================
                 * REAL MPGS (TEST / LIVE)
                 * ==========================
                 */
                Checkout.configure({
                    session: {
                        id: res.session_id,
                    },

                    interaction: {
                        merchant: {
                            name: document.title,
                        },
                        returnUrl: `${window.location.origin}/mashreq/return`,
                    },
                });

                Checkout.showPaymentPage();
            },

            error: function () {
                btn.prop("disabled", false).text("PLACE ORDER");
                alert("Payment initiation failed");
            },
        });
    });
});
