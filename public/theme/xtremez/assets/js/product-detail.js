$(function () {
    const baseCurrency = $('meta[name="currency"]').attr("content");
    const productId = $('meta[name="product-id"]').attr("content");
    let currentVariantId = $('meta[name="variant-id"]').attr("content");

    let basePrice = 0;

    // STEP 1: Get selected attributes
    function getSelectedAttributes() {
        const selected = {};
        $(".variant-option.active").each(function () {
            selected[$(this).data("attribute")] = $(this).data("value");
        });
        return selected;
    }

    // STEP 2: Auto-select valid combination
    function autoSelectCombination(attrs) {
        $.get("/ajax/match-variant", {
            product_id: productId,
            attribute_values: Object.values(attrs),
        }).done((res) => {
            if (!res.variant_id) return;

            // Update URL
            const url = new URL(window.location.href);
            url.searchParams.set("variant", res.variant_id);
            history.replaceState(null, "", url.toString());

            // Load full variant
            loadVariant(res.variant_id);
        });
    }

    // STEP 3: Load full variant content (image, price, etc.)
    function loadVariant(variantId) {
        $.get(`/ajax/products/${productId}/variant`, {
            variant: variantId,
        }).done((res) => {
            const variant = res.data.variant;

            currentVariantId = variant.id;
            basePrice = variant.price;

            // Update price
            const qty = parseInt($("#qtyInput").val()) || 1;
            $(".price").text(`${baseCurrency} ${(basePrice * qty).toFixed(2)}`);

            // Update image
            $("#zoomImage").attr("src", variant.images[0]);

            let thumbs = variant.images
                .map(
                    (img, i) =>
                        `<img src="${img}" data-large="${img}" class="thumb-item ${
                            i === 0 ? "active" : ""
                        } me-2" />`
                )
                .join("");

            $(".thumb-wrapper").html(thumbs);

            // Thumbnail click
            $(".thumb-item").on("click", function () {
                $("#zoomImage").attr("src", $(this).data("large"));
                $(".thumb-item").removeClass("active");
                $(this).addClass("active");
            });

            // Highlight matching attribute combination
            highlightAttributes(variant.attributes);
        });
    }

    // STEP 4: Highlight the selected attribute values
    function highlightAttributes(attributes) {
        $(".variant-option").removeClass("active");

        attributes.forEach((attr) => {
            $(
                `.variant-option[data-attribute="${slugify(
                    attr.attribute
                )}"][data-value="${attr.value}"]`
            ).addClass("active");
        });
    }

    // STEP 5: On attribute click, update selection and auto-match
    $(document).on("click", ".variant-option", function () {
        const attr = $(this).data("attribute");
        const val = $(this).data("value");

        // Remove current active from group
        $(`.variant-option[data-attribute="${attr}"]`).removeClass("active");
        $(this).addClass("active");

        const selectedAttrs = getSelectedAttributes();
        autoSelectCombination(selectedAttrs);
    });

    // STEP 6: Quantity +/- handling
    $("#qtyPlus").click(() => updateQty(1));
    $("#qtyMinus").click(() => updateQty(-1));
    $("#qtyInput").on("input", () => updateQty(0, true));

    function updateQty(change = 0, fromInput = false) {
        let qty = parseInt($("#qtyInput").val()) || 1;
        qty = fromInput ? qty : Math.max(1, qty + change);
        $("#qtyInput").val(qty);

        const total = qty * basePrice;
        $(".price").text(`${baseCurrency} ${total.toFixed(2)}`);
    }

    // STEP 7: Slug helper for matching data-attribute
    function slugify(str) {
        return str
            .toString()
            .toLowerCase()
            .replace(/\s+/g, "-")
            .replace(/[^\w\-]+/g, "")
            .replace(/\-\-+/g, "-")
            .trim();
    }

    // Init
    if (currentVariantId) loadVariant(currentVariantId);
});
