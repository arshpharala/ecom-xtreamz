$(document).ready(function () {
  const $carousel = $("#product-carousel").owlCarousel({
    loop: false,
    margin: 24,
    nav: false,
    dots: false,
    autoHeight: false,
    responsive: {
      0: { items: 1 },
      576: { items: 2 },
      768: { items: 3 },
      992: { items: 4 },
    }
  });

  // Store all products here
  let allProducts = [];

  // Fetch product data
  $.getJSON("assets/data/products.json", function (data) {
    allProducts = data;
    renderProducts("Clothing"); // Default
  });

  function renderProducts(category) {
    const filtered = allProducts.filter(p => p.category === category);


    $("#product-carousel").parents('section').find('.section-title').text(category);

    const html = filtered.map(product => {
      return `
        <div class="item" data-category="${product.category}">
          <div class="product-card h-100 d-flex flex-column align-items-center text-center">
            <div class="product-image-box mb-3">
              <img src="${product.image}" alt="${product.title}" class="img-fluid">
            </div>
            <div class="product-title-box">
              <h6 class="product-title">${product.title}</h6>
            </div>
            <div class="product-meta mt-auto d-flex justify-content-between align-items-center w-100 px-2">
              <span class="price fs-4 fw-bold">${product.price}</span>
              <button class="btn cart-btn"><i class="bi bi-cart"></i></button>
            </div>
          </div>
        </div>`;
    });

    $carousel.trigger("replace.owl.carousel", [html.join("")]).trigger("refresh.owl.carousel");
  }

  // Custom navigation
  $("#productCustomPrev").click(() => $carousel.trigger("prev.owl.carousel"));
  $("#productCustomNext").click(() => $carousel.trigger("next.owl.carousel"));

  // Filter on category click
  $(".category-item").on("click", function () {
    $(".category-icon").removeClass("active");
    $(this).find(".category-icon").addClass("active");

    const selectedCategory = $(this).data("category");
    renderProducts(selectedCategory);
  });
});