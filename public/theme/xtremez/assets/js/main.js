$(document).ready(function () {
    // $("#header").load("components/header-component.html"); // Load header
    // $("#footer").load("components/footer-component.html"); // Load footer

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $("body").on("click", "#mobileNavToggle", function () {
        $("body").find(".mobile-nav-drawer").addClass("active");
        $("body").addClass("has-active-menu");
    });

    $("body").on("click", ".nav-mask", function () {
        $("body").find(".mobile-nav-drawer").removeClass("active");
        $("body").removeClass("has-active-menu");
    });

    $(document).on("keydown", function (e) {
        if (e.key === "Escape") {
            $("body").find(".mobile-nav-drawer").removeClass("active");
            $("body").removeClass("has-active-menu");
        }
    });

    $(document).on("mouseup", function (event) {
        if (event.target.type === "radio" && event.target.checked === true) {
            setTimeout(function () {
                event.target.checked = false;
            }, 0);
        }
    });

    $(".type-placeholder").each(function () {
        const $input = $(this);
        const originalText = $input.attr("placeholder") || "";
        let i = 0;

        (function type() {
            if (i <= originalText.length) {
                $input.attr(
                    "placeholder",
                    originalText.slice(0, i) +
                        (i < originalText.length ? "|" : "")
                );
                i++;
                setTimeout(type, 30 + Math.random() * 170);
            }
        })();
    });
});

// $(".btn-primary").on("click", function (e) {
// 	e.preventDefault();
// 	$("html, body").animate(
// 		{
// 			scrollTop: $("#product-section").offset().top,
// 		},
// 		600
// 	);

// 	$(".add-to-cart-btn").on("click", function () {
// 		var productId = $(this).data("product-id");
// 		// You can trigger AJAX here if needed
// 		$(this).text("Added!").removeClass("btn-primary").addClass("btn-success");

// 		setTimeout(() => {
// 			$(this)
// 				.text("Add to Cart")
// 				.removeClass("btn-success")
// 				.addClass("btn-primary");
// 		}, 1500);
// 	});
// });

$(document).ready(function () {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    $(entry.target).addClass("in-view");
                    observer.unobserve(entry.target); // Animate only once
                }
            });
        },
        { threshold: 0.1 }
    );

    $(".animate-on-scroll").each(function () {
        observer.observe(this);
    });
});

// Sticky Header
const $header = $("#header");
const toggleClass = "is-sticky";
$(window).on("scroll", function () {
    const currentScroll = $(window).scrollTop();
    if (currentScroll > 150) {
        $header.addClass(toggleClass);
    } else {
        $header.removeClass(toggleClass);
    }
});

function debounce(func, delay = 300) {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), delay);
    };
}

function render_product_card(product, grid = false) {
    const hasOffer = product.offer_data?.has_offer;
    const offerText = hasOffer ? product.offer_data.label : "";
    const displayPrice = hasOffer
        ? product.offer_data.discounted_price
        : product.price;
    const originalPrice = hasOffer
        ? `<span class="text-muted text-decoration-line-through ms-2">${product.currency} ${product.price}</span>`
        : "";

    return `<div class="item ${grid}" data-category="${product.category}">
        <div class="product-card d-flex flex-column">
            <div class="image-box position-relative">
                <img src="${product.image}" alt="${
        product.name
    }" class="img-fluid"/>
                ${hasOffer ? `<div class="offer-badge">${offerText}</div>` : ""}
            </div>
            <div class="image_overlay"></div>
            <a href="${product.link}" class="overlay-button">View details</a>
            <div class="stats-container">
                <span class="product-title">${product.name}</span>
                <div class="product-description">
                    <p>${product.description}</p>
                </div>
                <div class="product-meta">
                    <span class="price fs-4 fw-bold">${
                        product.currency
                    } ${displayPrice}</span>
                    ${originalPrice}
                    <button class="btn cart-btn add-to-cart-btn ms-2" data-variant-id="${
                        product.id
                    }">
                        <i class="bi bi-cart add-to-cart" style="${
                            product.is_in_cart ? "display:none;" : ""
                        }"></i>
                        <i class="bi bi-cart-check added-to-cart" style="${
                            product.is_in_cart ? "" : "display:none;"
                        }"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>`;
}

function render_pagination(pagination) {
    const $pagination = $(".pagination");
    $pagination.empty();

    for (let i = 1; i <= pagination.last_page; i++) {
        const active = i === pagination.current_page ? "active" : "";
        $pagination.append(
            `<li class="page-item ${active}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`
        );
    }
}

$(document).on("change", "#province-select", function () {
    const provinceId = $(this).val();
    $("#city-select").html('<option value="">Loading...</option>');
    $("#area-select").html('<option value="">Select your area</option>');

    if (provinceId) {
        $.get(`${appUrl}/ajax/cities/${provinceId}`, function (data) {
            let options = '<option value="">Select your city</option>';
            data.forEach((city) => {
                options += `<option value="${city.id}">${city.name}</option>`;
            });
            $("#city-select").html(options);
        });
    }
});

$(document).on("change", "#city-select", function () {
    const cityId = $(this).val();
    $("#area-select").html('<option value="">Loading...</option>');

    if (cityId) {
        $.get(`${appUrl}/ajax/areas/${cityId}`, function (data) {
            let options = '<option value="">Select your area</option>';
            data.forEach((area) => {
                options += `<option value="${area.id}">${area.name}</option>`;
            });
            $("#area-select").html(options);
        });
    }
});

