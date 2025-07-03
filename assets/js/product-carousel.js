$(document).ready(function () {
  const $carousel = $("#product-carousel").owlCarousel({
    loop: false,
    margin: 24,
    nav: false,
    dots: false,
    autoHeight: false,
    items : 4,
    responsive: true,
    responsive: {
      0: { items: 1 },
      576: { items: 1 },
      768: { items: 2 },
      992: { items: 3 },
      1200: { items: 4 },
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
          <div class="product-card d-flex flex-column">
            <div class="image-box">
              <img src="${product.image}" alt="${product.title}"/>
            </div>
            <div class="image_overlay"></div>
            <a href="${product.link}" class="overlay-button">View details</a>

            <div class="stats-container">
              <span class="product-title">${product.title}</span>
              <div class="product-description">
                  <p>
                    ${product.description}
                  </p>
              </div>
              <div class="product-meta">
                <span class="price fs-4 fw-bold">${product.price}</span>
                <button class="btn cart-btn">
                  <i class="bi bi-cart"></i>
                </button>
              </div>
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