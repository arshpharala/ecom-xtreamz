const stripe = Stripe($('meta[name="stripe-key"]').attr("content"));
const elements = stripe.elements();
const card = elements.create("card");
card.mount("#card-element");

$(document).ready(function () {
    const $form = $("#checkout-form");
    const $submitBtn = $("#place-order-btn");
    const originalBtnText = $submitBtn.html();

    $form.on("submit", function (e) {
        const selectedMethod = $('input[name="payment_method"]:checked').val();
        const usingSavedCard = $('input[name="card_token"]:checked').length > 0;

        if (selectedMethod !== "card" || usingSavedCard) {
            return;
        }

        e.preventDefault();
        $("#card-errors").text("");

        const cardholderName = $("#cardName").val();
        if (!cardholderName) {
            $("#card-errors").text("Cardholder name is required.");
            return;
        }

        const formData = new FormData(this);

        $submitBtn.prop("disabled", true).html("Processing...");

        $.ajax({
            url: $form.attr("action"),
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: formData,
            processData: false,
            contentType: false,
            success: async function (res) {
                if (!res.clientSecret || !res.order_id) {
                    $("#card-errors").text("Invalid server response.");
                    restoreButton();
                    return;
                }

                const result = await stripe.confirmCardPayment(
                    res.clientSecret,
                    {
                        payment_method: {
                            card: card,
                            billing_details: { name: cardholderName },
                        },
                    }
                );

                if (result.error) {
                    $("#card-errors").text(result.error.message);
                    restoreButton();
                } else if (result.paymentIntent.status === "succeeded") {
                    $.post("/stripe/confirm-payment", {
                        order_id: res.order_id,
                        payment_intent_id: result.paymentIntent.id,
                        _token: $('meta[name="csrf-token"]').attr("content"),
                    })
                        .done(function (data) {
                            if (data.redirect) {
                                window.location.href = data.redirect;
                            } else {
                                $("#card-errors").text(
                                    "Payment confirmed but redirect missing."
                                );
                                restoreButton();
                            }
                        })
                        .fail(function () {
                            $("#card-errors").text(
                                "Payment succeeded but order confirmation failed."
                            );
                            restoreButton();
                        });
                }
            },
            error: function (xhr) {
                handleValidationErrors(xhr, $form);
                restoreButton();
            },
        });
    });

    function restoreButton() {
        $submitBtn.prop("disabled", false).html(originalBtnText);
    }
});
