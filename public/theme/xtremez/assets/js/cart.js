function updateCartSelectionState() {
    const $checkboxes = $(".cart-item:visible .form-check-input");
    const total = $checkboxes.length;
    const checked = $checkboxes.filter(":checked").length;

    // Update "Select All" checkbox
    $("#selectAll").prop("checked", checked === total && total > 0);

    // Show/hide clear button
    if (checked > 0) {
        $(".clear-cart").show();
    } else {
        $(".clear-cart").hide();
    }

    return { total, checked }; // optional return if you want to use the counts
}

function clearSelectedCartItems(onSuccess = null) {
    const selectedIds = $(".cart-item:visible .form-check-input:checked")
        .map(function () {
            return $(this).closest(".cart-item").data("variant-id");
        })
        .get();

    if (selectedIds.length === 0) {
        alert("Please select at least one item to remove.");
        return;
    }

    $.ajax({
        url: `${appUrl}/ajax/cart/clear-selected`,
        method: "DELETE",
        data: { variant_ids: selectedIds },
        success: function (res) {
            if (typeof onSuccess === "function") {
                onSuccess(res);
            }

            if (!res.cart || Object.keys(res.cart.items || {}).length === 0) {
                location.reload();
                return;
            }
        },
        error: function (res) {
            alert(
                res.responseJSON?.message || "Failed to clear selected items.",
            );
        },
    });
}

// When individual checkbox changes
$(".cart-items").on("change", ".form-check-input", function () {
    updateCartSelectionState();
});

// When "Select All" changes
$("#selectAll").on("change", function () {
    const checked = $(this).is(":checked");
    $(".cart-item:visible .form-check-input").prop("checked", checked);
    updateCartSelectionState();
});

$(".clear-cart a").on("click", function () {
    if (!confirm("Are you sure you want to remove selected items?")) return;

    clearSelectedCartItems(function (res) {
        updateCartCount(res.cart);
        $(".cart-item:visible .form-check-input:checked")
            .closest(".cart-item")
            .remove();
    });
});

$(".cart-items").on("click", ".qty-btn", function () {
    const $cartItem = $(this).closest(".cart-item");
    const variantId = $cartItem.data("variant-id");
    const $qtyBox = $cartItem.find(".cart-qty-val");

    let qty = $cartItem.data("qty") || 1;
    const isPlus = $(this).hasClass("plus");
    const newQty = isPlus ? qty + 1 : Math.max(1, qty - 1);

    updateCartVariantQty(variantId, newQty, function (res) {
        $cartItem.data("qty", newQty);
        $qtyBox.text(newQty);

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
        error: function (res) {
            if (res.responseJSON && res.responseJSON.message) {
                alert(res.responseJSON.message);
            } else {
                alert("Failed to update quantity.");
            }
        },
    });
}

function updateCartCount(cart) {
    const currencyCode = $("meta[name='currency']").attr("content");

    $("body").find("#cart-items-count").html(cart.count);
    if (cart.count > 0) {
        $("body").find("#cart-items-count").show();
    } else {
        $("body").find("#cart-items-count").hide();
    }

    $("body").find(".cart-total").html(cart.total_with_currency);
    $("body").find(".cart-sub-total").html(cart.subTotal_with_currency);
    $("body").find(".cart-taxes").html(cart.tax_with_currency);
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
        error: function (res) {
            if (res.responseJSON && res.responseJSON.message) {
                alert(res.responseJSON.message);
            } else {
                alert("Failed to add to cart.");
            }

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

    // Check if product has multiple variants
    $.ajax({
        url: `${appUrl}/ajax/check-multiple-variants`,
        method: "POST",
        data: { variant_id: variantId },
        success: function (res) {
            if (res.has_multiple_variants) {
                // Redirect to product detail page
                window.location.href = res.product_url;
            } else {
                // Add to cart directly
                addToCart(variantId, qty, function (success) {
                    if (success) {
                        $btn.find(".add-to-cart").hide();
                        $btn.find(".added-to-cart").show();
                    }
                });
            }
        },
        error: function () {
            alert("Failed to check product variants.");
        },
    });
});

$(document).on("click", ".buy-now-btn", function () {
    const $btn = $(this);
    const variantId = $btn.data("variant-id");
    const qty = parseInt($($btn.data("qty-selector")).val()) || 1;

    const isAlreadyInCart = $btn.hasClass("in-cart");

    if (isAlreadyInCart) {
        window.location.href = `${appUrl}/cart/`;
        return;
    }

    addToCart(variantId, qty, function (success) {
        if (success) {
            $btn.addClass("in-cart");
            window.location.href = `${appUrl}/cart/`;
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
