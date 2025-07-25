const stripe = Stripe($('meta[name="stripe-key"]').attr("content"));
const elements = stripe.elements();
const card = elements.create("card");
card.mount("#card-element");

$(document).ready(function () {
    const $form = $("#checkout-form");

    $form.on("submit", function (e) {
        const selectedMethod = $('input[name="payment_method"]:checked').val();
        const usingSavedCard = $('input[name="card_token"]:checked').length > 0;

        if (selectedMethod !== "card" || usingSavedCard) return;

        e.preventDefault();

        const cardholderName = $("#cardName").val();
        if (!cardholderName) {
            $("#card-errors").text("Cardholder name is required.");
            return;
        }

        const formData = new FormData(this);

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
                if (!res.clientSecret) {
                    alert("Something went wrong with the server response.");
                    return;
                }

                const result = await stripe.confirmCardPayment(res.clientSecret, {
                    payment_method: {
                        card: card,
                        billing_details: { name: cardholderName },
                    },
                });

                if (result.error) {
                    $("#card-errors").text(result.error.message);
                } else if (result.paymentIntent.status === "succeeded") {
                    window.location.href = `/order-summary/${res.order_number}`;
                }
            },
            error: function (xhr) {
                const msg = xhr.responseJSON?.message || "Payment failed. Please try again.";
                $("#card-errors").text(msg);
            },
        });
    });
});
