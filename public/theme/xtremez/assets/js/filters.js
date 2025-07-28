// ===============================
// Global State
// ===============================
let activeAttributeKeys = []; // Used to track current dynamic attributes for cleanup

// ===============================
// 1. Price Slider Initialization
// ===============================
function initPriceSlider(
    sliderId,
    labelMinId,
    labelMaxId,
    startMin = 5,
    startMax = 2000,
    onChange = null
) {
    const $slider = document.getElementById(sliderId);
    const $labelMin = $("#" + labelMinId);
    const $labelMax = $("#" + labelMaxId);

    if (!$slider) return;

    // Destroy previous instance if exists
    if ($slider.noUiSlider) $slider.noUiSlider.destroy();

    noUiSlider.create($slider, {
        start: [startMin, startMax],
        connect: true,
        range: { min: 0, max: 10000 },
        step: 1,
        format: {
            to: (value) => Math.round(value),
            from: (value) => Number(value),
        },
    });

    // Live update on slide
    $slider.noUiSlider.on("update", function (values) {
        $labelMin.text(values[0] + " AED");
        $labelMax.text(values[1] + " AED");
    });

    // Trigger product refresh after sliding ends
    if (onChange) {
        $slider.noUiSlider.on("change", function () {
            onChange();
        });
    }
}

$(function () {
    // ===============================
    // 2. Initialize Sidebar Slider
    // ===============================
    initPriceSlider(
        "price-slider-sidebar",
        "priceLabelMinSidebar",
        "priceLabelMaxSidebar",
        5,
        2000,
        () => fetchProducts(1)
    );

    // Modal slider (optional)
    $("#openFilterModal").on("click", function () {
        const modal = new bootstrap.Modal(
            document.getElementById("filterModal")
        );
        modal.show();

        setTimeout(() => {
            initPriceSlider(
                "price-slider-modal",
                "priceLabelMinModal",
                "priceLabelMaxModal"
            );
        }, 300);
    });

    // ===============================
    // 3. Read Filters from URL
    // ===============================
    function getFiltersFromURL() {
        const params = new URLSearchParams(window.location.search);
        let filters = {};

        for (let [key, value] of params.entries()) {
            if (key.endsWith("[]")) {
                const base = key.replace("[]", "");
                filters[base] = filters[base] || [];
                filters[base].push(value);
            } else {
                filters[key] = value;
            }
        }
        return filters;
    }

    // ===============================
    // 4. Update URL with Clean Filter Set
    // ===============================
    function updateURL(filters, page = 1) {
        const params = new URLSearchParams();

        // Remove old dynamic attribute filters
        for (let key of [...(filters ? Object.keys(filters) : [])]) {
            if (key.startsWith("attr_") && !activeAttributeKeys.includes(key)) {
                delete filters[key];
            }
        }
        // Apply new filters
        $.each(filters, function (key, value) {
            if (Array.isArray(value)) {
                value.forEach((val) => params.append(`${key}[]`, val));
            } else {
                params.set(key, value);
            }
        });

        params.set("page", page);
        history.pushState(null, "", "?" + params.toString());
    }

    // ===============================
    // 5. Collect Filter Data from UI
    // ===============================
    function collectFilters() {
        let filters = {};

        // Active category
        const activeCategory = $(".category-list li.active").data("category");
        if (activeCategory) filters.category_id = activeCategory;

        // All select inputs (including dynamic attributes)
        $(".theme-select").each(function () {
            const name = $(this).attr("name") || $(this).data("filter");
            if (name && $(this).val()) {
                filters[name] = $(this).val();
            }
        });

        // Tags
        filters.tags = $(".cc-form-check-input:checked")
            .map(function () {
                return $(this).next("label").text().trim();
            })
            .get();

        // Price
        filters.price_min = $("#priceLabelMinSidebar")
            .text()
            .replace("AED", "")
            .trim();
        filters.price_max = $("#priceLabelMaxSidebar")
            .text()
            .replace("AED", "")
            .trim();

        // Search & Sort
        filters.search = $(".search-input").val();
        filters.sort_by = $(".sort-select").val();

        return filters;
    }

    // ===============================
    // 6. AJAX Product Fetcher
    // ===============================
    function fetchProducts(page = 1) {
        const filters = collectFilters();
        updateURL(filters, page);

        $.ajax({
            url: window.ajaxProductURL,
            method: "GET",
            data: { ...filters, page },
            success: function (res) {
                if (res.success) {
                    renderProducts(res.data.products);
                    render_pagination(res.data.pagination);
                }
            },
        });
    }

    // ===============================
    // 7. Render Product Grid
    // ===============================
    function renderProducts(products) {
        const $container = $("#products");
        $container.empty();

        products.forEach((product) => {
            const html = render_product_card(
                product,
                "col-12 col-sm-6 col-md-6 col-lg-6 col-xl-4"
            );
            $container.append(html);
        });
    }


    // ===============================
    // 8. Render Attribute Filters Dynamically
    // ===============================
    function renderDynamicAttributeFilters(attributes) {
        const $container = $("#dynamic-attribute-filters");
        const urlParams = new URLSearchParams(window.location.search);

        $container.empty();
        activeAttributeKeys = []; // Reset tracked keys

        attributes.forEach((attr) => {
            const key = `attr_${attr.id}`;
            activeAttributeKeys.push(key);

            const selectedValue = urlParams.get(key) || "";

            const $wrapper = $(
                `<div class="mb-4"><h5 class="fs-3 mb-3">${attr.name}</h5></div>`
            );
            const $select =
                $(`<select class="form-select theme-select" name="${key}" data-attribute="${attr.id}">
                              <option value="">Select ${attr.name}</option>
                           </select>`);

            $.each(attr.values, function (id, val) {
                const selected = id === selectedValue ? "selected" : "";
                $select.append(
                    `<option value="${id}" ${selected}>${val}</option>`
                );
            });

            $wrapper.append($select);
            $container.append($wrapper);
        });
    }

    // ===============================
    // 10. Load Attributes for Selected Category
    // ===============================
    function loadInitialAttributeFilters(categoryId) {
        if (!categoryId) return;

        $.get(`/ajax/category/${categoryId}/attributes`, function (res) {
            if (res.success) {
                renderDynamicAttributeFilters(res.attributes);
            }
        });
    }

    // ===============================
    // 11. Event Bindings
    // ===============================
    // Category click
    $(document).on("click", ".category-list li", function () {
        $(".category-list li").removeClass("active");
        $(this).addClass("active");

        const categoryId = $(this).data("category");
        loadInitialAttributeFilters(categoryId);
        // Reset sidebar slider to default
        initPriceSlider(
            "price-slider-sidebar",
            "priceLabelMinSidebar",
            "priceLabelMaxSidebar",
            5,
            2000,
            () => fetchProducts(1)
        );
        fetchProducts(1);
    });

    // Static filters (brand, color, sort, search)
    $(".theme-select, .cc-form-check-input").on("change", () =>
        fetchProducts(1)
    );

    // Dynamic filters (attribute dropdowns)
    $(document).on(
        "change",
        "#dynamic-attribute-filters select.theme-select",
        () => fetchProducts(1)
    );

    // Pagination click
    $(document).on("click", ".pagination .page-link", function (e) {
        e.preventDefault();
        const page = $(this).data("page");
        if (page) fetchProducts(page);
    });

    // ===============================
    // 12. Initial Page Load
    // ===============================
    const initialPage =
        parseInt(new URLSearchParams(window.location.search).get("page")) || 1;
    fetchProducts(initialPage);
    loadInitialAttributeFilters(window.activeCategoryId);
});
