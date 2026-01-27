$(function () {
    const thumbItemWidth = $(".thumb-item").outerWidth(true);
    const $thumbWrapper = $(".thumb-wrapper");
    const $prevBtn = $("#thumbPrev");
    const $nextBtn = $("#thumbNext");
    const visibleCount = 4;
    const productSelector = $(".product-detail");

    productSelector.on("click", ".qty-btn", function () {
        const isPlus = $(this).hasClass("plus");
        let qty = parseInt($("#qtyInput").val()) || 1;
        const newQty = isPlus ? qty + 1 : Math.max(1, qty - 1);

        $("#qtyInput").val(newQty);
        productSelector.data("qty", newQty);
        productSelector.attr("data-qty", newQty);

        updateCartVariantQty(window.variant.id, newQty, function (res) {
            if (res.variant) {
                updateCartCount(res.cart);
                window.variant = res.variant;
                // updatePriceDisplay(); // Do not update price here
            } else {
                alert("Unable to update variant.");
            }
        });
    });

    // function updatePriceDisplay() {
    //     try {
    //         let price = parseFloat(window.variant?.price) || 0;
    //         let subtotal = parseFloat(window.variant?.cart_item?.subtotal);

    //         const total = !isNaN(subtotal) ? subtotal : price;

    //         $("#priceDisplay").text(
    //             `${$("meta[name='currency']").attr("content")} ${total.toFixed(
    //                 2
    //             )}`
    //         );
    //     } catch (err) {
    //         console.error("Error updating price display:", err);
    //         $("#priceDisplay").text(
    //             `${$("meta[name='currency']").attr("content")} --`
    //         );
    //     }
    // }

    function updatePriceDisplay() {
        try {
            const variant = window.variant;
            const currency = $("meta[name='currency']").attr("content");

            const hasOffer = variant?.offer_data?.has_offer === true;
            const discountedPrice = parseFloat(
                variant?.offer_data?.discounted_price || 0,
            );
            const originalPrice = parseFloat(variant?.price || 0);
            const offerLabel = variant?.offer_data?.label || "";
            const subtotal = parseFloat(variant?.cart_item?.subtotal);

            let html = "";

            if (hasOffer && discountedPrice > 0) {
                html += `<span class="text-danger fw-bold ms-2">${variant?.offer_data?.discounted_price_with_currency}</span>`;
                html += `<span class="text-muted text-decoration-line-through ms-2">${variant?.price_with_currency}</span>`;
                html += `<span class="badge bg-secondary ms-2">${offerLabel}</span>`;
            } else {
                // html += `<span>${formatPrice(currency, originalPrice)}</span>`;
                html += `<span>${variant?.price_with_currency}</span>`;
            }
            // if (hasOffer && discountedPrice > 0) {
            //     html += `<span class="text-danger fw-bold ms-2">${formatPrice(
            //         currency,
            //         discountedPrice
            //     )}</span>`;
            //     html += `<span class="text-muted text-decoration-line-through ms-2">${formatPrice(
            //         currency,
            //         originalPrice
            //     )}</span>`;
            //     html += `<span class="badge bg-secondary ms-2">${offerLabel}</span>`;
            // } else {
            //     html += `<span>${formatPrice(currency, originalPrice)}</span>`;
            // }

            $("#priceDisplay").html(html);
        } catch (err) {
            console.error("Error updating price display:", err);
            const fallbackCurrency = $("meta[name='currency']").attr("content");
            $("#priceDisplay").html(
                `<span>${formatPrice(fallbackCurrency, 0)}</span>`,
            );
        }
    }

    // function updatePriceDisplay() {
    //     try {
    //         const variant = window.variant;
    //         const currency = $("meta[name='currency']").attr("content");

    //         const hasOffer = variant?.offer_data?.has_offer === true;
    //         const discountedPrice = parseFloat(
    //             variant?.offer_data?.discounted_price || 0
    //         );
    //         const originalPrice = parseFloat(variant?.price || 0);
    //         const offerLabel = variant?.offer_data?.label || "";
    //         const subtotal = parseFloat(variant?.cart_item?.subtotal);

    //         let html = "";

    //         // 1. Show cart subtotal if available
    //         // if (!isNaN(subtotal)) {
    //         //     html += `<span class="text-danger fw-bold">${currency} ${subtotal.toFixed(
    //         //         2
    //         //     )}</span>`;

    //         //     if (
    //         //         hasOffer &&
    //         //         discountedPrice > 0 &&
    //         //         subtotal < originalPrice
    //         //     ) {
    //         //         html += `<span class="text-muted text-decoration-line-through ms-2">${currency} ${originalPrice.toFixed(
    //         //             2
    //         //         )}</span>`;
    //         //         html += `<span class="badge bg-secondary ms-2">${offerLabel}</span>`;
    //         //     }

    //         //     // 2. If no cart subtotal, show discounted offer
    //         // } else
    //             if (hasOffer && discountedPrice > 0) {
    //             html += `<span class="text-danger fw-bold">${currency} ${discountedPrice.toFixed(
    //                 2
    //             )}</span>`;
    //             html += `<span class="text-muted text-decoration-line-through ms-2">${currency} ${originalPrice.toFixed(
    //                 2
    //             )}</span>`;
    //             html += `<span class="badge bg-secondary ms-2">${offerLabel}</span>`;

    //             // 3. Fallback to base price
    //         } else {
    //             html += `<span>${currency} ${originalPrice.toFixed(2)}</span>`;
    //         }

    //         $("#priceDisplay").html(html);
    //     } catch (err) {
    //         console.error("Error updating price display:", err);
    //         $("#priceDisplay").html(
    //             `<span>${$("meta[name='currency']").attr("content")} --</span>`
    //         );
    //     }
    // }

    function getSelectedAttributes() {
        const selected = {};
        $(".variant-option.active").each(function () {
            selected[$(this).data("attr")] = $(this).data("value");
        });
        return selected;
    }

    // function findMatchingVariant(partialSelection) {
    //     const variants = window.allVariants;
    //     return variants.find((variant) => {
    //         return Object.entries(partialSelection).every(
    //             ([key, val]) => variant.combination[key] === val
    //         );
    //     });
    // }

    function updateSelectedAttributesFromVariant(variant) {
        if (!variant || !variant.combination) return;

        $(".variant-option").removeClass("active");

        for (const [attr, value] of Object.entries(variant.combination)) {
            $(
                `.variant-option[data-attr="${attr}"][data-value="${value}"]`,
            ).addClass("active");
        }
    }

    function updateCartButtonState(variant) {
        const isInCart = !!variant?.cart_item;

        const $addBtn = $(".add-to-cart-btn");

        if (isInCart) {
            $addBtn.find(".add-to-cart").hide();
            $addBtn.find(".added-to-cart").show();
            $addBtn.addClass("in-cart");
        } else {
            $addBtn.find(".add-to-cart").show();
            $addBtn.find(".added-to-cart").hide();
            $addBtn.removeClass("in-cart");
        }
    }

    function updateAddToCartButtonState() {
        const qty = parseInt($("#qtyInput").val()) || 0;
        const $addToCartBtn = $(".add-to-cart-btn");

        if (qty > 0) {
            $addToCartBtn.prop("disabled", false);
        } else {
            $addToCartBtn.prop("disabled", true);
        }
    }

    function updateVariantDisplay() {
        let variant = window.variant;
        window.basePrice = parseFloat(variant.price);
        window.currentVariantId = variant.variant_id;

        try {
            const variantId = variant?.variant_id;
            const qty = variant?.cart_item?.qty ?? 1;

            if (variantId) {
                // Update variant-id for all relevant buttons
                $(".buy-now-btn, .add-to-cart-btn, .cart-item")
                    .data("variant-id", variantId)
                    .attr("data-variant-id", variantId);
            }

            // Update qty only for cart-item
            $("#qtyInput").val(qty);
            $(".cart-item").data("qty", qty).attr("data-qty", qty);
        } catch (err) {
            console.error("Error updating variant button attributes:", err);
        }

        $(".product-description p").html(variant.description || "");

        if (variant.images.length) {
            $("#zoomImage").attr("src", variant.images[0]);
            const thumbs = variant.images.map(
                (src, idx) =>
                    `<img src="${src}" data-large="${src}" class="thumb-item ${
                        idx === 0 ? "active" : ""
                    } me-2"/>`,
            );
            $(".thumb-wrapper").html(thumbs.join(""));
        }

        // Update Stock Badge
        const stockBadge = $(".main-image").find(".stock-badge");
        if (stockBadge.length) {
            if (variant.stock > 0) {
                stockBadge
                    .removeClass("out-of-stock")
                    .find(".stock-text")
                    .html(`In Stock: <strong>${variant.stock}</strong>`);
                stockBadge.show();
            } else {
                stockBadge
                    .addClass("out-of-stock")
                    .find(".stock-text")
                    .html("Out of Stock");
                stockBadge.show();
            }
        }

        if (variant.packagings && variant.packagings.length) {
            const getPackagingValue = (label) => {
                const item = variant.packagings.find((p) =>
                    p.name.toLowerCase().includes(label.toLowerCase()),
                );
                return item ? item.pivot.value : "NA";
            };

            $(".specs-table")
                .find("#product-qty-per-carton")
                .text(getPackagingValue("qty"));

            $(".specs-table")
                .find("#product-carton-gross-weight")
                .text(getPackagingValue("gross weight"));

            $(".specs-table")
                .find("#product-carton-dimenssions")
                .text(getPackagingValue("dimension"));

            $(".specs-table")
                .find("#product-sku")
                .text(variant.sku ?? "NA");
        }

        updateCartButtonState(variant);

        updatePriceDisplay();
    }

    function fetchVariant(selectedAttributes) {
        const productId = $('meta[name="product-id"]').attr("content");

        $.ajax({
            url: window.ajaxVarianrURL, // already defined in <script>
            method: "GET",
            data: {
                product_id: productId,
                attributes: selectedAttributes,
            },
            success: function (response) {
                window.variant = response;
                updateSelectedAttributesFromVariant(response);
                updateVariantDisplay();
                updateUrlVariantId();
                // Update size variants after variant change
                if (typeof window.updateSizeVariants === "function") {
                    window.updateSizeVariants();
                }
            },
            error: function () {
                alert("This combination is currently not available.");
            },
        });
    }

    // function updateUrlVariantId() {
    //     const url = new URL(window.location.href);
    //     url.searchParams.set("variant", window.variant.id);
    //     history.pushState({}, "", url);
    // }

    function updateUrlVariantId() {
        const url = new URL(window.location.href);
        const productSlug = url.pathname.split("/")[2];
        const newPath = `/products/${productSlug}/variant/${window.variant.id}`;
        history.pushState({}, "", newPath);
    }

    // Init qty price on load
    // updatePriceDisplay();

    // $("#qtyPlus").click(() => {
    //     qty += 1;
    //     $("#qtyInput").val(qty);
    //     updatePriceDisplay();
    // });

    // $("#qtyMinus").click(() => {
    //     qty = qty > 1 ? qty - 1 : 1;
    //     $("#qtyInput").val(qty);
    //     updatePriceDisplay();
    // });

    // $("#qtyInput").on("input", function () {
    //     qty = parseInt($(this).val()) || 1;
    //     updatePriceDisplay();
    // });

    // Initial selection highlight
    $(".variant-option").each(function () {
        const attr = $(this).data("attr"),
            val = $(this).data("value");
        if (window.selectedAttributes[attr] === val) {
            $(this).addClass("active");
        }
    });

    // Initialize add-to-cart button state
    updateAddToCartButtonState();

    // Handle manual input changes on quantity field
    $("#qtyInput").on("input", function () {
        updateAddToCartButtonState();
    });

    // Variant switch
    $(document).on("click", ".variant-option", function () {
        const attr = $(this).data("attr");
        const value = $(this).data("value");

        // 1. Get current selection
        let currentSelection = getSelectedAttributes();

        // 2. Rebuild object with latest clicked attribute last
        const updatedSelection = {};
        Object.entries(currentSelection).forEach(([k, v]) => {
            if (k !== attr) updatedSelection[k] = v;
        });
        updatedSelection[attr] = value;

        fetchVariant(updatedSelection);
    });

    function updateThumbNav() {
        const scrollLeft = $thumbWrapper.scrollLeft();
        const maxScroll =
            $thumbWrapper[0].scrollWidth - $thumbWrapper.outerWidth();

        const hasOverflow = $thumbWrapper.children().length > visibleCount;
        $prevBtn.toggle(hasOverflow && scrollLeft > 5);
        $nextBtn.toggle(hasOverflow && scrollLeft < maxScroll - 5);
    }

    function getThumbWidth() {
        const $first = $thumbWrapper.find(".thumb-item").first();
        return $first.outerWidth(true);
    }

    $nextBtn.on("click", () => {
        const w = getThumbWidth();
        $thumbWrapper.animate({ scrollLeft: "+=" + w }, 200, updateThumbNav);
    });

    $prevBtn.on("click", () => {
        const w = getThumbWidth();
        $thumbWrapper.animate({ scrollLeft: "-=" + w }, 200, updateThumbNav);
    });

    $thumbWrapper.on("scroll resize", updateThumbNav);
    $(window).on("resize", updateThumbNav);
    updateThumbNav();

    // Thumbnail switching
    $(document).on("click", ".thumb-item", function () {
        $(".thumb-item").removeClass("active");
        $(this).addClass("active");

        const largeImg = $(this).data("large");
        $("#zoomImage").attr("src", largeImg);
    });

    // Recalculate on resize
    $(window).on("resize", updateThumbNav);
    updateThumbNav();
});
