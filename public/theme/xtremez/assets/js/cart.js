$(function () {
    // Select All
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

    // Update Qty
    $(".cart-items").on("click", ".qty-btn", function () {
        const $cartItem = $(this).closest(".cart-item");
        const variantId = $cartItem.data("variant-id");
        const $qtyBox = $cartItem.find(".cart-qty-val");
        let qty = $cartItem.data("qty");

        const isPlus = $(this).hasClass("plus");
        const newQty = isNaN(qty) ? 1 : isPlus ? qty + 1 : Math.max(1, qty - 1);

        $qtyBox.text(newQty);
        $cartItem.data("qty", newQty);
        updateQuantity(variantId, newQty, $qtyBox);
    });

    // Delete Item
    $(".cart-items").on("click", ".btn-trash", function () {
        const $cartItem = $(this).closest(".cart-item");
        const variantId = $cartItem.data("variant-id");

        $.ajax({
            url: `/cart/${variantId}`,
            method: "DELETE",
            success: function () {
                $cartItem.remove();
                syncSelectAll();
            },
        });
    });

    function updateQuantity(variantId, qty, $qtyBox) {
        $.ajax({
            url: `/cart/${variantId}`,
            method: "PUT",
            data: {
                variant_id: variantId,
                qty: qty,
            },
            success: function () {
                $qtyBox.text(qty);

                const $cartItem = $qtyBox.closest(".cart-item");
                const unitPrice = parseFloat($cartItem.data("price"));
                if (!isNaN(unitPrice)) {
                    const totalPrice = (unitPrice * qty).toFixed(2);
                    $cartItem.find(".item-total").text(`${totalPrice} AED`);
                }
            },
        });
    }

    function syncSelectAll() {
        const $checkboxes = $(".cart-item:visible .form-check-input");
        const total = $checkboxes.length;
        const checked = $checkboxes.filter(":checked").length;
        $("#selectAll").prop("checked", checked === total);
    }

    $(function () {
        function addToCart(variantId, qty, callback) {
            $.ajax({
                url: "/cart",
                method: "POST",
                data: {
                    variant_id: variantId,
                    qty: qty,
                },
                success: function (res) {
                    if (res.success) {
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

        // Add to Cart Button
        $(document).on("click", ".add-to-cart-btn", function () {
            const variantId = $(this).data("variant-id");
            const qty = parseInt($($(this).data("qty-selector")).val()) || 1;

            addToCart(variantId, qty, function (success) {
                if (success) {
                    alert("Product added to cart!");
                } else {
                    alert("Failed to add to cart.");
                }
            });
        });

        // Buy Now Button
        $(document).on("click", ".buy-now-btn", function () {
            const variantId = $(this).data("variant-id");
            const qty = parseInt($($(this).data("qty-selector")).val()) || 1;

            addToCart(variantId, qty, function (success) {
                if (success) {
                    window.location.href = "/cart";
                } else {
                    alert("Failed to proceed.");
                }
            });
        });
    });
});
