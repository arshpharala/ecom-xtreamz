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
    const payload = buildAddToCartPayload({
        variant_id: variantId,
        qty: qty,
    });

    if (!payload) {
        callback(false);
        return;
    }

    addToCartRequest(payload)
        .done(function (res) {
            if (res.success) {
                updateCartCount(res.cart);
                callback(true);
            } else {
                callback(false);
            }
        })
        .fail(function (res) {
            if (res.responseJSON && res.responseJSON.message) {
                alert(res.responseJSON.message);
            } else {
                alert("Failed to add to cart.");
            }

            callback(false);
        });
}

function getCustomizationData() {
    const $toggle = $("#customizationEnabled");
    if ($toggle.length === 0 || !$toggle.is(":checked")) {
        return null;
    }

    const notes = ($("#customizationNotes").val() || "").trim();
    const files =
        Array.isArray(window.customizationImages) &&
            window.customizationImages.length > 0
            ? window.customizationImages.map((item) => item.file).filter(Boolean)
            : (() => {
                const input = document.getElementById("customizationImages");
                return input && input.files
                    ? Array.from(input.files)
                    : [];
            })();

    return { notes, files };
}

// Generate UUID for customization
function generateUUID() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
}

function buildAddToCartPayload(fields) {
    const customization = getCustomizationData();

    // If no customization, just return standard data
    if (!customization || (!customization.notes && customization.files.length === 0)) {
        return { data: fields, useFormData: false };
    }

    if (customization.files.length > 5) {
        alert("You can upload up to 5 images only.");
        return null;
    }

    // Prepare payload
    // We will still use FormData just to be consistent, but we won't attach files
    // actually, we can just use simple object if we aren't sending files
    const payload = { ...fields };
    payload.customization_enabled = "1";

    if (customization.notes) {
        payload.customization_text = customization.notes;
    }

    // Handle Client-Side Storage for Images
    if (customization.files.length > 0) {
        const customizationId = generateUUID();
        payload.customization_id = customizationId;

        // Save to IDB
        // Note: This is async. We might need to handle this differently if builtAddToCartPayload is expected to be sync.
        // However, looking at the calls (e.g. in size-quantity-handler), it expects a return value immediately.
        // We have to initiate the save here, but we can't block.
        // Ideally, we should change the flow to be async, but to minimize refactor:
        // We will trigger the save. If it fails, the user might see missing images in cart.
        // For better UX, we should probably start the save and return.

        window.IDB.save(customizationId, customization.files).then(() => {
            console.log("Customization images saved to IDB:", customizationId);
        }).catch(err => {
            console.error("Failed to save customization images:", err);
            alert("Failed to save images locally. Please try again.");
        });
    }

    return { data: payload, useFormData: false }; // No FormData needed if we don't send files
}


function addToCartRequest(payload, urlOverride = null) {
    const url = urlOverride || `${appUrl}/cart`;

    return $.ajax({
        url,
        method: "POST",
        data: payload.data,
        processData: !payload.useFormData,
        contentType: payload.useFormData ? false : undefined, // let jquery handle it if not FormData
    });
}

// Load customization images from IDB on Cart Page
function loadCustomizationImagesWithIDB() {
    $(".customization-details").each(function () {
        const $container = $(this);
        const customizationId = $container.data("customization-id");

        if (!customizationId) return;

        // Check if we already loaded it (avoid duplicate logic if re-run)
        if ($container.find(".custom-images-loaded").length > 0) return;

        console.log("Loading images for:", customizationId);

        window.IDB.get(customizationId).then(files => {
            if (files && files.length > 0) {
                const $imgContainer = $("<div>").addClass('mt-2');
                $imgContainer.append('<small class="fw-bold text-uppercase text-muted d-block mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Branding Assets</small>');
                const $flex = $('<div class="d-flex gap-2 flex-wrap custom-images-loaded"></div>');

                files.forEach(file => {
                    const url = URL.createObjectURL(file);
                    const $link = $('<a>')
                        .attr('href', url)
                        .attr('target', '_blank');

                    const $img = $('<img>')
                        .attr('src', url)
                        .addClass('border rounded')
                        .css({ width: '40px', height: '40px', objectFit: 'cover' });

                    $link.append($img);
                    $flex.append($link);
                });

                $imgContainer.append($flex);
                $container.append($imgContainer);
            }
        }).catch(err => console.error("Error loading IDB images:", err));
    });
}

window.buildAddToCartPayload = buildAddToCartPayload;
window.addToCartRequest = addToCartRequest;

$(document).ready(function () {
    // Attempt to load images if on cart page
    loadCustomizationImagesWithIDB();
});


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
