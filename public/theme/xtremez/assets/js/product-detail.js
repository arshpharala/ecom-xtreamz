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
                updatePriceDisplay();
            } else {
                alert("Unable to update variant.");
            }
        });
    });

    function updatePriceDisplay() {
        try {
            let price = parseFloat(window.variant?.price) || 0;
            let subtotal = parseFloat(window.variant?.cart_item?.subtotal);

            const total = !isNaN(subtotal) ? subtotal : price;

            $("#priceDisplay").text(
                `${$("meta[name='currency']").attr("content")} ${total.toFixed(
                    2
                )}`
            );
        } catch (err) {
            console.error("Error updating price display:", err);
            $("#priceDisplay").text(
                `${$("meta[name='currency']").attr("content")} --`
            );
        }
    }

    function getSelectedAttributes() {
        const selected = {};
        $(".variant-option.active").each(function () {
            selected[$(this).data("attr")] = $(this).data("value");
        });
        return selected;
    }

    function findMatchingVariant(partialSelection) {
        const variants = window.allVariants;
        return variants.find((variant) => {
            return Object.entries(partialSelection).every(
                ([key, val]) => variant.combination[key] === val
            );
        });
    }

    function updateSelectedAttributesFromVariant(variant) {
        if (!variant || !variant.combination) return;

        $(".variant-option").removeClass("active");

        for (const [attr, value] of Object.entries(variant.combination)) {
            $(
                `.variant-option[data-attr="${attr}"][data-value="${value}"]`
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

        $("#priceDisplay").text(
            `${$("meta[name='currency']").attr("content")} ${variant.price}`
        );
        $(".product-description p").html(variant.description || "");

        if (variant.images.length) {
            $("#zoomImage").attr("src", variant.images[0]);
            const thumbs = variant.images.map(
                (src, idx) =>
                    `<img src="${src}" data-large="${src}" class="thumb-item ${
                        idx === 0 ? "active" : ""
                    } me-2"/>`
            );
            $(".thumb-wrapper").html(thumbs.join(""));
        }

        if (variant.shipping) {
            $(".specs-table")
                .find("#product-qty-per-carton")
                .text(`${variant.shipping.qty_per_carton ?? "NA"} pcs`);
            $(".specs-table")
                .find("#product-carton-gross-weight")
                .text(`${variant.shipping.weight ?? "NA"} kgs`);
            $(".specs-table")
                .find("#product-carton-dimenssions")
                .text(
                    `${variant.shipping.length ?? "NA"} x ${
                        variant.shipping.width ?? "NA"
                    } x ${variant.shipping.height ?? "NA"} cm`
                );
            $(".specs-table")
                .find("#product-sku")
                .text(`${variant.sku ?? "NA"}`);
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
            },
            error: function () {
                alert("This combination is currently not available.");
            },
        });
    }

    function updateUrlVariantId() {
        const url = new URL(window.location.href);
        url.searchParams.set("variant", window.variant.id);
        history.pushState({}, "", url);
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
