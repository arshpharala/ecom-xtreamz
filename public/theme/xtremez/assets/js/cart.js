$("#selectAll").on("change", function () {
    const checked = $(this).is(":checked");
    $(".cart-item:visible .form-check-input").prop("checked", checked);
});

// Sync checkbox with Select All
$(".cart-items").on("change", ".form-check-input", function () {
    const $checkboxes = $(".cart-item:visible .form-check-input");
    const total = $checkboxes.length;
    const checked = $checkboxes.filter(":checked").length;
    $("#selectAll").prop("checked", checked === total);
});

$(".cart-items").on("click", ".qty-btn", function () {
    const $cartItem = $(this).closest(".cart-item");
    const variantId = $cartItem.data("variant-id");
    const $qtyBox = $cartItem.find(".cart-qty-val");

    let qty = $cartItem.data("qty") || 1;
    const isPlus = $(this).hasClass("plus");
    const newQty = isPlus ? qty + 1 : Math.max(1, qty - 1);

    $cartItem.data("qty", newQty);
    $qtyBox.text(newQty);

    updateCartVariantQty(variantId, newQty, function (res) {
        updateCartCount(res.cart);
        if (res.message) {
            alert(res.message); // or use toast
        }
    });
});

// Delete Item
$(".cart-items").on("click", ".btn-trash", function () {
    const $cartItem = $(this).closest(".cart-item");
    const variantId = $cartItem.data("variant-id");

    $.ajax({
        url: `${appUrl}/cart/${variantId}`,
        method: "DELETE",
        success: function (res) {
            if (res.message) {
                alert(res.message); // or use toast
            }
            $cartItem.remove();
            syncSelectAll();

            updateCartCount(res.cart);
        },
    });
});

// function updateQuantity(variantId, qty, $qtyBox) {
//     $.ajax({
//         url: `/cart/${variantId}`,
//         method: "PUT",
//         data: {
//             variant_id: variantId,
//             qty: qty,
//         },
//         success: function (res) {
//             $qtyBox.text(qty);
//             $qtyBox.val(qty);

//             // const $cartItem = $qtyBox.closest(".cart-item");
//             // const unitPrice = parseFloat($cartItem.data("price"));

//             // if (!isNaN(unitPrice)) {
//             //     const totalPrice = (unitPrice * qty).toFixed(2);
//             // }

//             updateCartCount(res.cart);
//         },
//     });
// }

// Shared function
function updateCartVariantQty(variantId, qty, onSuccess = null) {
    $.ajax({
        url: `${appUrl}/cart/${variantId}`,
        method: "PUT",
        data: { variant_id: variantId, qty: qty },
        success: function (res) {
            if (typeof onSuccess === "function") {
                onSuccess(res);
            }
        },
        error: function () {
            alert("Failed to update quantity.");
        },
    });
}

function updateCartCount(cart) {
    $("body").find("#cart-items-count").text(cart.count);
    $("body")
        .find(".cart-total")
        .text(`${cart.total.toFixed(2)} AED`);
    $("body")
        .find(".cart-sub-total")
        .text(`${cart.subTotal.toFixed(2)} AED`);
    $("body")
        .find(".cart-taxes")
        .text(`${cart.tax.toFixed(2)} AED`);
}

function syncSelectAll() {
    const $checkboxes = $(".cart-item:visible .form-check-input");
    const total = $checkboxes.length;
    const checked = $checkboxes.filter(":checked").length;
    $("#selectAll").prop("checked", checked === total);
}

function addToCart(variantId, qty, callback) {
    $.ajax({
        url: `${appUrl}/cart`,
        method: "POST",
        data: {
            variant_id: variantId,
            qty: qty,
        },
        success: function (res) {
            if (res.success) {
                updateCartCount(res.cart);
                callback(true);
            } else {
                callback(false);
            }
        },
        error: function () {
            callback(false);
        },
    });
}

$(document).on("click", ".add-to-cart-btn", function () {
    const $btn = $(this);

    if ($btn.find(".added-to-cart").is(":visible")) {
        console.log("Already in cart. Quantity can be updated on cart page.");
        return;
    }

    const variantId = $btn.attr("data-variant-id");
    const qty = parseInt($($btn.data("qty-selector")).val()) || 1;

    addToCart(variantId, qty, function (success) {
        if (success) {
            $btn.find(".add-to-cart").hide();
            $btn.find(".added-to-cart").show();
        } else {
            alert("Failed to add to cart.");
        }
    });
});

$(document).on("click", ".buy-now-btn", function () {
    const $btn = $(this);
    const variantId = $btn.data("variant-id");
    const qty = parseInt($($btn.data("qty-selector")).val()) || 1;

    const isAlreadyInCart = $btn.hasClass("in-cart");

    if (isAlreadyInCart) {
        window.location.href = "/cart";
        return;
    }

    addToCart(variantId, qty, function (success) {
        if (success) {
            $btn.addClass("in-cart");
            window.location.href = "/cart";
        } else {
            alert("Failed to proceed.");
        }
    });
});

$(document).on("click", ".btn-apply", function () {
    const code = $(".cart-summary input").val();

    $.ajax({
        url: `${appUrl}/ajax/coupon/apply`,
        method: "POST",
        data: { code: code },
        success: function (res) {
            alert("Coupon applied!");
            window.location.reload();
        },
        error: function (xhr) {
            alert(xhr.responseJSON.message || "Failed to apply coupon.");
        },
    });
});

$(document).on("click", ".remove-coupon", function () {
    $.ajax({
        url: `${appUrl}/ajax/coupon/remove`,
        method: "POST",
        success: function () {
            alert("Coupon removed");
            window.location.reload();
        },
        error: function () {
            alert("Failed to remove coupon");
        },
    });
});
