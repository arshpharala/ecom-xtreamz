$(document).ready(function () {
    let categoryTitle;
    const $carousel = $("#product-carousel").owlCarousel({
        loop: false,
        margin: 24,
        nav: false,
        dots: false,
        autoHeight: false,
        items: 4,
        responsive: {
            0: { items: 1 },
            576: { items: 1 },
            768: { items: 2 },
            992: { items: 3 },
            1200: { items: 4 },
        },
    });

    // Reusable fetch function
    function fetchCategoryProducts(categoryId) {
        $.ajax({
            url: window.ajaxProductURL, // already defined in <script>
            method: "GET",
            data: {
                category_id: categoryId,
                limit: 10, // or 8, or whatever you want
            },
            success: function (res) {
                if (res.success && res.data.products.length) {
                    renderCarouselProducts(res.data.products);
                    // updateSectionTitle(res.data.products[0].category_name);
                } else {
                    renderCarouselProducts([]);
                    updateSectionTitle("No Products");
                }
            },
        });
    }

    //  Render the returned product HTML in carousel
    function renderCarouselProducts(products) {
        const html = products.map((product) => render_product_card(product)); // returns array of strings

        $carousel
            .trigger("replace.owl.carousel", [html.join("")]) // PASS array, not a single string
            .trigger("refresh.owl.carousel");
    }

    // Update section title
    function updateSectionTitle(categoryTitle) {
        $("#product-carousel")
            .parents("section")
            .find(".section-title")
            .text(categoryTitle);
    }

    // Initial load (first category active)
    const firstCategoryId = $(".category-item").first().data("category-id");
    categoryTitle = $(".category-item").first().data("category");
    if (firstCategoryId) {
        fetchCategoryProducts(firstCategoryId);
        updateSectionTitle(categoryTitle);
    }

    // On category click
    $(document).on("click", ".category-item", function () {
        $(".category-icon").removeClass("active");
        $(this).find(".category-icon").addClass("active");

        const selectedCategoryId = $(this).data("category-id");
        categoryTitle = $(this).data("category");
        updateSectionTitle(categoryTitle);
        fetchCategoryProducts(selectedCategoryId);
    });

    // Carousel Nav Buttons
    $("#productCustomPrev").click(() => $carousel.trigger("prev.owl.carousel"));
    $("#productCustomNext").click(() => $carousel.trigger("next.owl.carousel"));
});
