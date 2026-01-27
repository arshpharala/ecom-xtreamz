/**
 * Size Quantity Selector Handler
 * Handles multi-size quantity selection and cart operations
 */

$(document).ready(function () {
    const $sizeSelector = $(".size-quantity-selector");

    if ($sizeSelector.length === 0) {
        return; // No size selector on this page
    }

    // Function to update size variant data based on current non-size attributes
    window.updateSizeVariants = function () {
        if (!window.allVariants) return;

        const selectedAttrs = {};
        // Get all selected attributes except 'size'
        $(".variant-option.active").each(function () {
            const attr = $(this).data("attr");
            if (attr !== "size") {
                selectedAttrs[attr] = $(this).data("value");
            }
        });

        const $sizeInputs = $(".size-qty-input");

        $sizeInputs.each(function () {
            const $input = $(this);
            const sizeValue = $input.data("size");
            const targetCombination = { ...selectedAttrs, size: sizeValue };

            // Find matching variant from allVariants
            const match = window.allVariants.find((v) => {
                // Check if all attributes in targetCombination match the variant's combination
                return Object.entries(targetCombination).every(
                    ([attr, val]) => {
                        return v.combination[attr] == val; // Use == for loose comparison if needed
                    },
                );
            });

            const $item = $input.closest(".size-qty-item");
            const $stockInfo = $item.find(".stock-info");

            if (match) {
                $input.data("variant-id", match.id);
                $input.data("stock", match.stock);
                $input.attr("max", match.stock);

                if (match.stock > 0) {
                    $item.removeClass("out-of-stock");
                    $input.prop("disabled", false);
                    $stockInfo.html(`Stock: ${match.stock}`);
                } else {
                    $item.addClass("out-of-stock");
                    $input.prop("disabled", true).val(0);
                    $stockInfo.html("Out of Stock");
                }
            } else {
                // No variant found for this combination
                $item.addClass("out-of-stock");
                $input.prop("disabled", true).val(0);
                $stockInfo.html("Unavailable");
            }
        });
    };

    // Function to update add-to-cart button state based on size quantities
    function updateAddToCartButtonState() {
        const totalQty = $(".size-qty-input")
            .toArray()
            .reduce((sum, input) => {
                return sum + (parseInt($(input).val()) || 0);
            }, 0);

        const $addToCartBtn = $(".add-to-cart-btn");
        if (totalQty > 0) {
            $addToCartBtn.prop("disabled", false);
        } else {
            $addToCartBtn.prop("disabled", true);
        }
    }

    // Initialize size variants on load
    setTimeout(updateSizeVariants, 300);
    // Initialize button state
    updateAddToCartButtonState();

    // Listen for attribute changes (e.g., color) to update size variants
    $(document).on("click", ".variant-option", function () {
        if ($(this).data("attr") !== "size") {
            // small delay to let product-detail.js update 'active' class
            setTimeout(updateSizeVariants, 150);
        }
    });

    // Handle "Add to Cart" button click for size-based products
    $(".add-to-cart-btn").on("click", function (e) {
        if ($sizeSelector.length > 0) {
            e.preventDefault();
            e.stopPropagation();

            const sizeQuantities = [];
            $(".size-qty-input").each(function () {
                const $input = $(this);
                const qty = parseInt($input.val()) || 0;
                const variantId = $input.data("variant-id");
                const size = $input.data("size");
                const stock = $input.data("stock");

                if (qty > 0 && variantId) {
                    if (qty > stock) {
                        Swal.fire({
                            icon: "warning",
                            title: "Insufficient Stock",
                            text: `Only ${stock} units available for size ${size}`,
                        });
                        return false;
                    }

                    sizeQuantities.push({
                        variant_id: variantId,
                        qty: qty,
                        size: size,
                    });
                }
            });

            if (sizeQuantities.length === 0) {
                Swal.fire({
                    icon: "info",
                    title: "No Quantity Selected",
                    text: "Please enter quantities for at least one size",
                });
                return;
            }

            addMultipleSizesToCart(sizeQuantities, $(this));
        }
    });

    // Handle "Buy Now" button click for size-based products
    $(".buy-now-btn").on("click", function (e) {
        if ($sizeSelector.length > 0) {
            e.preventDefault();
            e.stopPropagation();

            const sizeQuantities = [];
            $(".size-qty-input").each(function () {
                const $input = $(this);
                const qty = parseInt($input.val()) || 0;
                const variantId = $input.data("variant-id");
                const size = $input.data("size");
                const stock = $input.data("stock");

                if (qty > 0 && variantId) {
                    if (qty > stock) {
                        Swal.fire({
                            icon: "warning",
                            title: "Insufficient Stock",
                            text: `Only ${stock} units available for size ${size}`,
                        });
                        return false;
                    }

                    sizeQuantities.push({
                        variant_id: variantId,
                        qty: qty,
                        size: size,
                    });
                }
            });

            if (sizeQuantities.length === 0) {
                Swal.fire({
                    icon: "info",
                    title: "No Quantity Selected",
                    text: "Please enter quantities for at least one size",
                });
                return;
            }

            addMultipleSizesToCart(sizeQuantities, $(this), true);
        }
    });

    function addMultipleSizesToCart(sizeQuantities, $button, buyNow = false) {
        const originalText = $button.html();
        $button
            .prop("disabled", true)
            .html('<i class="bi bi-hourglass-split"></i> Adding...');

        let promises = sizeQuantities.map((item) => {
            return $.post(appUrl + "/cart/add", {
                product_variant_id: item.variant_id,
                qty: item.qty,
            });
        });

        Promise.all(promises)
            .then((responses) => {
                const totalQty = sizeQuantities.reduce(
                    (sum, item) => sum + item.qty,
                    0,
                );

                if (responses.length > 0) {
                    $("#cart-count").text(responses[0].cart.count);
                    $(".cart-count").text(responses[0].cart.count);
                    $("#cart-items-count").text(responses[0].cart.count);
                    if (responses[0].cart.count > 0) {
                        $("#cart-items-count").show();
                    } else {
                        $("#cart-items-count").hide();
                    }
                }

                Swal.fire({
                    icon: "success",
                    title: "Added to Cart!",
                    text: `${totalQty} item(s) across ${sizeQuantities.length} size(s) added successfully`,
                    timer: 2000,
                    showConfirmButton: false,
                });

                $button.html(
                    '<span class="added-to-cart">Added to Cart</span>',
                );

                if (buyNow) {
                    setTimeout(() => {
                        window.location.href = appUrl + "/checkout";
                    }, 1500);
                } else {
                    // Reset inputs after adding to cart
                    $(".size-qty-input").val(0);
                    setTimeout(() => {
                        $button.prop("disabled", false).html(originalText);
                    }, 2000);
                }
            })
            .catch((error) => {
                console.error("Error adding to cart:", error);
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Failed to add items to cart. Please try again.",
                });
                $button.prop("disabled", false).html(originalText);
            });
    }

    $(".size-qty-input").on("keypress", function (e) {
        if (e.which === 13) {
            e.preventDefault();
            const $inputs = $(".size-qty-input:not(:disabled)");
            const currentIndex = $inputs.index(this);
            const $nextInput = $inputs.eq(currentIndex + 1);

            if ($nextInput.length) {
                $nextInput.focus().select();
            } else {
                $(".add-to-cart-btn").click();
            }
        }
    });

    $(".size-qty-input").on("focus", function () {
        $(this).select();
    });

    // Update add-to-cart button state when size quantity inputs change
    $(document).on("input", ".size-qty-input", function () {
        updateAddToCartButtonState();
    });
});
