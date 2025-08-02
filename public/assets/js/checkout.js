$(document).ready(function () {
    const $cardSection = $("#cardSection");
    const $paypalContainer = $("#paypal-button-container");
    const $placeOrderBtn = $("#place-order-button");

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
        } else {
            $cardSection.hide();
            $paypalContainer.hide();
            $placeOrderBtn.show();
        }
    });

    // On page load, toggle if paypal is pre-selected
    const selected = $('input[name="payment_method"]:checked').val();
    if (selected === "paypal") {
        $cardSection.hide();
        $paypalContainer.show();
        $placeOrderBtn.hide();
    }
});
