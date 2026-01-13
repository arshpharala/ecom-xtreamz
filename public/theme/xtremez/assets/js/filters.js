// ===============================
// Global State
// ===============================
let activeAttributeKeys = []; // Used to track current dynamic attributes for cleanup
let currencySymbol = $("meta[name='currency-symbol']").attr("content") || "AED";

const $productscontainer = $("#products");
const $noProductContainer = $("#no-products");

// ===============================
// 1. Price Slider Initialization
// ===============================
function initPriceSlider(
    sliderId,
    labelMinId,
    labelMaxId,
    startMin = 0,
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
        $labelMin.html(currencySymbol + " " + values[0]);
        $labelMax.html(currencySymbol + " " + values[1]);
    });
    // $slider.noUiSlider.on("update", function (values) {
    //     $labelMin.html(values[0] + " " + currencySymbol);
    //     $labelMax.html(values[1] + " " + currencySymbol);
    // });

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
        0,
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

        // Active category - find from parent, child, or grandchild
        const $activeCategory = $(
            ".category-list .parent-category.active, .category-list .child-category.active, .category-list .grandchild-category.active"
        ).first();
        const activeCategory = $activeCategory.data("category-slug");
        if (activeCategory) filters.category = activeCategory;

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
            .replace(/[^0-9.,]/g, "")
            .replace(/,/g, "")
            .trim();
        filters.price_max = $("#priceLabelMaxSidebar")
            .text()
            .replace(/[^0-9.,]/g, "")
            .replace(/,/g, "")
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
            beforeSend: function () {
                $productscontainer.empty();
                $("#loader").show();
            },
            success: function (res) {
                if (res.success) {
                    renderProducts(res.data.products);
                    render_pagination(res.data.pagination);
                }
            },
            complete: function () {
                $("#loader").hide();
            },
        });
    }

    // ===============================
    // 7. Render Product Grid
    // ===============================
    function renderProducts(products) {
        $productscontainer.empty();
        $noProductContainer.hide();

        if (products.length > 0) {
            $productscontainer.show();
            products.forEach((product) => {
                const html = render_product_card(
                    product,
                    "col-12 col-sm-6 col-md-6 col-lg-6 col-xl-4"
                );
                $productscontainer.append(html);
            });
        } else {
            $productscontainer.hide();
            $noProductContainer.show();
        }
    }

    // ===============================
    // 8. Render Attribute Filters Dynamically
    // ===============================
    // function renderDynamicAttributeFilters(attributes) {
    //     const $container = $("#dynamic-attribute-filters");
    //     const urlParams = new URLSearchParams(window.location.search);

    //     $container.empty();
    //     activeAttributeKeys = []; // Reset tracked keys

    //     attributes.forEach((attr) => {
    //         const key = `attr_${attr.id}`;
    //         activeAttributeKeys.push(key);

    //         const selectedValue = urlParams.get(key) || "";

    //         const $wrapper = $(
    //             `<div class="mb-4"><h5 class="fs-3 mb-3">${attr.name}</h5></div>`
    //         );
    //         const $select =
    //             $(`<select class="form-select theme-select" name="${key}" data-attribute="${attr.id}">
    //                           <option value="">Select ${attr.name}</option>
    //                        </select>`);

    //         $.each(attr.values, function (id, val) {
    //             const selected = id === selectedValue ? "selected" : "";
    //             $select.append(
    //                 `<option value="${id}" ${selected}>${val}</option>`
    //             );
    //         });

    //         $wrapper.append($select);
    //         $container.append($wrapper);
    //     });
    // }

    function renderDynamicAttributeFilters(attributes) {
        const $containers = $(".dynamic-attribute-filters"); // all containers
        const urlParams = new URLSearchParams(window.location.search);

        activeAttributeKeys = []; // Reset tracked keys

        // clear all containers first
        $containers.empty();

        attributes.forEach((attr) => {
            const key = `attr_${attr.id}`;
            activeAttributeKeys.push(key);

            const selectedValue = urlParams.get(key) || "";

            const $wrapper = $(`
            <div class="mb-4">
                <h5 class="fs-3 mb-3">${attr.name}</h5>
            </div>
        `);

            const $select = $(`
            <select class="form-select theme-select" name="${key}" data-attribute="${attr.id}">
                <option value="">Select ${attr.name}</option>
            </select>
        `);

            $.each(attr.values, function (id, val) {
                const selected = id == selectedValue ? "selected" : "";
                $select.append(
                    `<option value="${id}" ${selected}>${val}</option>`
                );
            });

            $wrapper.append($select);

            // append to each container
            $containers.each(function () {
                $(this).append($wrapper.clone()); // clone ensures independent copies
            });
        });
    }

    // ===============================
    // 10. Load Attributes for Selected Category
    // ===============================
    function loadInitialAttributeFilters(categoryId) {
        if (!categoryId) return;

        $.get(
            appUrl + `/ajax/category/${categoryId}/attributes`,
            function (res) {
                if (res.success) {
                    renderDynamicAttributeFilters(res.attributes);
                }
            }
        );
    }

    // ===============================
    // 11. Event Bindings
    // ===============================
    // Category parent click - toggle expand/collapse and select category
    $(document).on("click", ".category-list .parent-category", function (e) {
        e.stopPropagation();

        const $parentCategory = $(this);
        const $categoryItem = $parentCategory.closest(".category-item");
        const $childrenList = $categoryItem.find("> .category-children");
        const hasChildren = $parentCategory.hasClass("has-children");

        // Remove active from all categories
        $(
            ".category-list .parent-category, .category-list .child-category, .category-list .grandchild-category"
        ).removeClass("active");

        // Set this parent as active
        $parentCategory.addClass("active");

        // Toggle children expansion
        if (hasChildren) {
            $childrenList.toggleClass("expanded");
        }

        // Collapse all other parent level-1 lists
        $(".category-list > .parent-item > .category-children.level-1")
            .not($childrenList)
            .removeClass("expanded");

        // Load filters and fetch products
        const categoryId = $parentCategory.data("category");
        loadInitialAttributeFilters(categoryId);
        initPriceSlider(
            "price-slider-sidebar",
            "priceLabelMinSidebar",
            "priceLabelMaxSidebar",
            0,
            2000,
            () => fetchProducts(1)
        );
        fetchProducts(1);
    });

    // Category child click - select child and keep parent open
    $(document).on("click", ".category-list .child-category", function (e) {
        e.stopPropagation();

        const $childCategory = $(this);
        const $childItem = $childCategory.closest(".category-item");
        const $level1List = $childItem.closest(".category-children.level-1");
        const $level2List = $childItem.find("> .category-children.level-2");
        const hasChildren = $childCategory.hasClass("has-children");

        // Remove active from all categories
        $(
            ".category-list .parent-category, .category-list .child-category, .category-list .grandchild-category"
        ).removeClass("active");

        // Set this child as active
        $childCategory.addClass("active");

        // Ensure level1 is open
        $level1List.addClass("expanded");

        // Toggle level 2 expansion for this child if it has children
        if (hasChildren) {
            $level2List.toggleClass("expanded");
        }

        // Load filters and fetch products
        const categoryId = $childCategory.data("category");
        loadInitialAttributeFilters(categoryId);
        initPriceSlider(
            "price-slider-sidebar",
            "priceLabelMinSidebar",
            "priceLabelMaxSidebar",
            0,
            2000,
            () => fetchProducts(1)
        );
        fetchProducts(1);
    });

    // Category grandchild click - select grandchild and keep all parents open
    $(document).on(
        "click",
        ".category-list .grandchild-category",
        function (e) {
            e.stopPropagation();

            const $grandchildCategory = $(this);
            const $level2List = $grandchildCategory.closest(
                ".category-children.level-2"
            );
            const $level1List = $level2List
                .closest(".category-item")
                .closest(".category-children.level-1");

            // Remove active from all categories
            $(
                ".category-list .parent-category, .category-list .child-category, .category-list .grandchild-category"
            ).removeClass("active");

            // Set this grandchild as active
            $grandchildCategory.addClass("active");

            // Open all parent levels
            $level1List.addClass("expanded");
            $level2List.addClass("expanded");

            // Load filters and fetch products
            const categoryId = $grandchildCategory.data("category");
            loadInitialAttributeFilters(categoryId);
            initPriceSlider(
                "price-slider-sidebar",
                "priceLabelMinSidebar",
                "priceLabelMaxSidebar",
                0,
                2000,
                () => fetchProducts(1)
            );
            fetchProducts(1);
        }
    );

    // Search
    let searchTimer;
    $(".search-input").on("keyup", function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function () {
            fetchProducts(1);
        }, 500);
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
        $("html, body").animate({ scrollTop: 0 }, 100);
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
