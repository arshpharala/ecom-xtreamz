(function ($) {
    "use strict";

    // meanmenu
    $('#mobile-menu').meanmenu({
        meanMenuContainer: '.mobile-menu',
        meanScreenWidth: "992"
    });

    // info-bar
    $('.info-bar').on('click', function () {
        $('.extra-info').addClass('info-open');
    })

    $('.close-icon').on('click', function () {
        $('.extra-info').removeClass('info-open');
    })

    // data - background
    $("[data-background]").each(function () {
        $(this).css("background-image", "url(" + $(this).attr("data-background") + ")")
    })
    // data - color
    $("[data-bg-color]").each(function () {
        $(this).css("background", $(this).attr("data-bg-color"))
    })

    // cat - toggle
    $('.cat-toggle').on('click', function () {
        $('.category-menu').slideToggle(500);
    });


    // sticky
    var wind = $(window);
    var sticky = $('#sticky-header');
    wind.on('scroll', function () {
        var scroll = wind.scrollTop();
        if (scroll < 100) {
            sticky.removeClass('sticky');
        } else {
            sticky.addClass('sticky');
        }
    });


    // active
    $('.service-box').on('mouseenter', function () {
        $(this).addClass('active').parent().siblings().find('.service-box').removeClass('active');
    })




    // mainSlider
    function mainSlider() {
        var BasicSlider = $('.slider-active');
        BasicSlider.on('init', function (e, slick) {
            var $firstAnimatingElements = $('.single-slider:first-child').find('[data-animation]');
            doAnimations($firstAnimatingElements);
        });
        BasicSlider.on('beforeChange', function (e, slick, currentSlide, nextSlide) {
            var $animatingElements = $('.single-slider[data-slick-index="' + nextSlide + '"]').find('[data-animation]');
            doAnimations($animatingElements);
        });
        BasicSlider.slick({
            autoplay: false,
            autoplaySpeed: 10000,
            dots: false,
            fade: true,
            arrows: true,
            prevArrow: '<button type="button" class="slick-prev"><i class="far fa-long-arrow-left"></i></button>',
            nextArrow: '<button type="button" class="slick-next"><i class="far fa-long-arrow-right"></i></button>',
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        arrows: false,
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        arrows: false,
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        arrows: false,
                    }
                }
            ]
        });

        function doAnimations(elements) {
            var animationEndEvents = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
            elements.each(function () {
                var $this = $(this);
                var $animationDelay = $this.data('delay');
                var $animationType = 'animated ' + $this.data('animation');
                $this.css({
                    'animation-delay': $animationDelay,
                    '-webkit-animation-delay': $animationDelay
                });
                $this.addClass($animationType).one(animationEndEvents, function () {
                    $this.removeClass($animationType);
                });
            });
        }
    }
    mainSlider();


    // test-active
    $('.test-active').slick({
        dots: true,
        arrows: true,
        infinite: true,
        autoplay: true,
        speed: 300,
        prevArrow: '<button type="button" class="slick-prev"><i class="fal fa-long-arrow-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="fal fa-long-arrow-right"></i></button>',
        slidesToShow: 2,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 1500,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 550,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
    // test-02-active
    $('.test-02-active').slick({
        dots: true,
        arrows: true,
        infinite: true,
        autoplay: true,
        speed: 300,
        prevArrow: '<button type="button" class="slick-prev"><i class="fal fa-long-arrow-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="fal fa-long-arrow-right"></i></button>',
        slidesToShow: 1,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 1500,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                }
            },
            {
                breakpoint: 550,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                }
            }
        ]
    });
    // test-03-active
    $('.test-03-active').slick({
        dots: false,
        arrows: true,
        infinite: true,
        autoplay: true,
        speed: 300,
        prevArrow: '<button type="button" class="slick-prev"><i class="fal fa-long-arrow-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="fal fa-long-arrow-right"></i></button>',
        slidesToShow: 1,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 1500,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                }
            },
            {
                breakpoint: 550,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                }
            }
        ]
    });
    // instagram-active
    $('.instagram-active').slick({
        dots: false,
        arrows: false,
        infinite: true,
        autoplay: true,
        speed: 300,
        prevArrow: '<button type="button" class="slick-prev"><i class="fal fa-long-arrow-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="fal fa-long-arrow-right"></i></button>',
        slidesToShow: 6,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 1500,
                settings: {
                    slidesToShow: 6,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 5,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 550,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
    // banners-active
    $('.banners-active').slick({
        dots: true,
        arrows: false,
        infinite: true,
        speed: 300,
        prevArrow: '<button type="button" class="slick-prev"><i class="fal fa-long-arrow-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="fal fa-long-arrow-right"></i></button>',
        slidesToShow: 1,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 1500,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 550,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: false,
                }
            }
        ]
    });
    // pro-active
    $('.pro-active').slick({
        dots: false,
        arrows: false,
        infinite: true,
        speed: 300,
        prevArrow: '<button type="button" class="slick-prev"><i class="fal fa-long-arrow-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="fal fa-long-arrow-right"></i></button>',
        slidesToShow: 3,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 1500,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 550,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });



    // brand-active
    $('.brand-active').slick({
        dots: false,
        arrows: false,
        infinite: true,
        autoplay: true,
        speed: 300,
        prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-arrow-left"></i></button>',
        nextArrow: '<button type="button" class="slick-next"><i class="fas fa-arrow-right"></i></button>',
        slidesToShow: 5,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 5,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 550,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });



    /* counter */
    $('.counter').counterUp({
        delay: 10,
        time: 1000
    });

    /* magnificPopup video view */
    $('.popup-video').magnificPopup({
        type: 'iframe'
    });

    /* magnificPopup img view */
    $('.popup-image').magnificPopup({
        type: 'image',
        gallery: {
            enabled: true
        }
    });


    $('#portfolio-grid').imagesLoaded(function () {
        // init Isotope
        var $grid = $('#portfolio-grid').isotope({
            itemSelector: '.grid-item',
            percentPosition: true,
            masonry: {
                // use outer width of grid-sizer for columnWidth
                columnWidth: 1
            }
        });
        // filter items on button click
        $('.portfolio-menu').on('click', 'button', function () {
            var filterValue = $(this).attr('data-filter');
            $grid.isotope({ filter: filterValue });
        });
    });


    //for menu active class
    $('.portfolio-menu button').on('click', function (event) {
        $(this).siblings('.active').removeClass('active');
        $(this).addClass('active');
        event.preventDefault();
    });


    // countdown
    $('[data-countdown]').each(function () {
        var $this = $(this), finalDate = $(this).data('countdown');
        $this.countdown(finalDate, function (event) {
            $this.html(event.strftime('<div class="time-count">%D <span>days</span></div><div class="time-count">%H <span>hour</span></div><div class="time-count">%M <span>minute</span></div><div class="time-count">%S <span>Second</span></div>'));
        });
    });


    // scrollToTop
    $.scrollUp({
        scrollName: 'scrollUp', // Element ID
        topDistance: '300', // Distance from top before showing element (px)
        topSpeed: 300, // Speed back to top (ms)
        animation: 'fade', // Fade, slide, none
        animationInSpeed: 200, // Animation in speed (ms)
        animationOutSpeed: 200, // Animation out speed (ms)
        scrollText: '<i class="fas fa-angle-up"></i>', // Text for element
        activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
    });

    // WOW active
    new WOW().init();

    // map
    function basicmap() {
        // Basic options for a simple Google Map
        // For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
        var mapOptions = {
            // How zoomed in you want the map to start at (always required)
            zoom: 11,
            scrollwheel: false,
            // The latitude and longitude to center the map (always required)
            center: new google.maps.LatLng(40.6700, -73.9400), // New York
            // This is where you would paste any style found on Snazzy Maps.
            styles: [{ "featureType": "all", "elementType": "geometry.fill", "stylers": [{ "weight": "2.00" }] }, { "featureType": "all", "elementType": "geometry.stroke", "stylers": [{ "color": "#9c9c9c" }] }, { "featureType": "all", "elementType": "labels.text", "stylers": [{ "visibility": "on" }] }, { "featureType": "landscape", "elementType": "all", "stylers": [{ "color": "#f2f2f2" }] }, { "featureType": "landscape", "elementType": "geometry.fill", "stylers": [{ "color": "#ffffff" }] }, { "featureType": "landscape.man_made", "elementType": "geometry.fill", "stylers": [{ "color": "#ffffff" }] }, { "featureType": "poi", "elementType": "all", "stylers": [{ "visibility": "off" }] }, { "featureType": "road", "elementType": "all", "stylers": [{ "saturation": -100 }, { "lightness": 45 }] }, { "featureType": "road", "elementType": "geometry.fill", "stylers": [{ "color": "#eeeeee" }] }, { "featureType": "road", "elementType": "labels.text.fill", "stylers": [{ "color": "#7b7b7b" }] }, { "featureType": "road", "elementType": "labels.text.stroke", "stylers": [{ "color": "#ffffff" }] }, { "featureType": "road.highway", "elementType": "all", "stylers": [{ "visibility": "simplified" }] }, { "featureType": "road.arterial", "elementType": "labels.icon", "stylers": [{ "visibility": "off" }] }, { "featureType": "transit", "elementType": "all", "stylers": [{ "visibility": "off" }] }, { "featureType": "water", "elementType": "all", "stylers": [{ "color": "#46bcec" }, { "visibility": "on" }] }, { "featureType": "water", "elementType": "geometry.fill", "stylers": [{ "color": "#c8d7d4" }] }, { "featureType": "water", "elementType": "labels.text.fill", "stylers": [{ "color": "#070707" }] }, { "featureType": "water", "elementType": "labels.text.stroke", "stylers": [{ "color": "#ffffff" }] }]
        };
        // Get the HTML DOM element that will contain your map
        // We are using a div with id="map" seen below in the <body>
        var mapElement = document.getElementById('contact-map');

        // Create the Google Map using our element and options defined above
        var map = new google.maps.Map(mapElement, mapOptions);

        // Let's also add a marker while we're at it
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(40.6700, -73.9400),
            map: map,
            title: 'Cryptox'
        });
    }
    if ($('#contact-map').length != 0) {
        google.maps.event.addDomListener(window, 'load', basicmap);
    }


    if (typeof ($.fn.knob) != 'undefined') {
        $('.knob').each(function () {
            var $this = $(this),
                knobVal = $this.attr('data-rel');

            $this.knob({
                'draw': function () {
                    $(this.i).val(this.cv + '%')
                }
            });

            $this.appear(function () {
                $({
                    value: 0
                }).animate({
                    value: knobVal
                }, {
                    duration: 2000,
                    easing: 'swing',
                    step: function () {
                        $this.val(Math.ceil(this.value)).trigger('change');
                    }
                });
            }, {
                accX: 0,
                accY: -150
            });
        });
    }


    /*----- cart-plus-minus-button -----*/
    $(".cart-plus-minus").append('<div class="dec qtybutton">-</div><div class="inc qtybutton">+</div>');
    $(".qtybutton").on("click", function () {
        var $button = $(this);
        var oldValue = $button.parent().find("input").val();
        if ($button.text() == "+") {
            var newVal = parseFloat(oldValue) + 1;
        } else {
            // Don't allow decrementing below zero
            if (oldValue > 0) {
                var newVal = parseFloat(oldValue) - 1;
            } else {
                newVal = 0;
            }
        }
        $button.parent().find("input").val(newVal);
    });


    /*-------------------------
        showlogin toggle function
    --------------------------*/
    $('#showlogin').on('click', function () {
        $('#checkout-login').slideToggle(900);
    });

    /*-------------------------
        showcoupon toggle function
    --------------------------*/
    $('#showcoupon').on('click', function () {
        $('#checkout_coupon').slideToggle(900);
    });

    /*-------------------------
        Create an account toggle function
    --------------------------*/
    $('#cbox').on('click', function () {
        $('#cbox_info').slideToggle(900);
    });

    /*-------------------------
        Create an account toggle function
    --------------------------*/
    $('#ship-box').on('click', function () {
        $('#ship-box-info').slideToggle(1000);
    });

})(jQuery);


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

// function render_product_card(product, grid = false) {
//     const hasOffer = product.offer_data?.has_offer;
//     const offerText = hasOffer ? product.offer_data.label : "";

//     const currentCurrency = product.currency; // e.g., 'USD'
//     const discountedPrice = parseFloat(product.offer_data?.discounted_price || 0);
//     const basePrice = parseFloat(product.price);

//     const displayPrice = hasOffer && discountedPrice > 0
//         ? formatPrice(currentCurrency, discountedPrice)
//         : formatPrice(currentCurrency, basePrice);

//     const originalPrice = hasOffer
//         ? `<span class="text-muted text-decoration-line-through ms-2"> ${formatPrice(currentCurrency, basePrice)}</span>`
//         : "";

//     return `<div class="item ${grid}" data-category="${product.category}">
//         <div class="product-card d-flex flex-column">
//             <div class="image-box position-relative">
//                 <img src="${product.image}" alt="${product.name}" class="img-fluid"/>
//                 ${hasOffer ? `<div class="offer-badge">${offerText}</div>` : ""}
//             </div>
//             <div class="image_overlay"></div>
//             <a href="${product.link}" class="overlay-button">View details</a>
//             <div class="stats-container">
//                 <span class="product-title">${product.name}</span>
//                 <div class="product-description">
//                     <p>${product.description}</p>
//                 </div>
//                 <div class="product-meta">
//                     <span class="price fs-4 fw-bold">${displayPrice}</span>
//                     ${originalPrice}
//                     <button class="btn cart-btn add-to-cart-btn ms-2" data-variant-id="${product.id}">
//                         <i class="bi bi-cart add-to-cart" style="${product.is_in_cart ? "display:none;" : ""}"></i>
//                         <i class="bi bi-cart-check added-to-cart" style="${product.is_in_cart ? "" : "display:none;"}"></i>
//                     </button>
//                 </div>
//             </div>
//         </div>
//     </div>`;
// }

function render_product_card(product, grid = true) {
    const hasOffer = product.offer_data?.has_offer;
    const offerText = hasOffer ? product.offer_data.label : "";
    const displayPrice = hasOffer && product.offer_data.discounted_price > 0 ?
        product.offer_data.discounted_price_with_currency :
        product.price_with_currency;
    const originalPrice = hasOffer ?
        `<span class="text-muted text-decoration-line-through ms-2">${product.price_with_currency}</span>` :
        "";

    // Optional flags
    const isWishlisted = !!product.is_wishlisted;
    const isHot = product.tags?.includes("Hot");
    const isNew = product.tags?.includes("New");

    // Grid Card
    if (grid) {
        return `
        <div class="col-xl-3 col-lg-3 col-md-6">
            <div class="product-03-wrapper grey-2-bg pos-rel text-center mb-30">
                <div class="badge-tag">
                    ${isHot ? `<span class="product-tag pro-tag hot-1">Hot</span>` : ""}
                    ${isNew ? `<span class="product-tag pro-tag hot-2">New</span>` : ""}
                    ${offerText ? `<span class="product-tag pro-tag">${offerText}</span>` : ""}
                </div>
                <div class="product-02-img pos-rel">
                    <a href="${product.link}">
                        <img src="${product.image}" alt="${product.name}">
                    </a>
                    <div class="product-action">
                        <button class="action-btn wishlist-btn ${isWishlisted ? 'is-active' : ''}"
                                data-variant-id="${product.id}">
                            <i class="far fa-heart"></i>
                        </button>
                        <button class="action-btn add-to-cart-btn" data-variant-id="${product.id}">
                            <i class="far fa-cart-plus"></i>
                        </button>
                        <a class="action-btn" href="${product.link}">
                            <i class="far fa-search"></i>
                        </a>
                    </div>
                </div>
                <div class="product-text">
                    <h5>${product.category || ''}</h5>
                    <h4><a href="${product.link}">${product.name}</a></h4>
                    <span class="fw-bold">${displayPrice}</span>
                    ${originalPrice}
                </div>
            </div>
        </div>`;
    }

    // List Card
    return `
    <div class="col-xl-12">
        <div class="product-list-content mb-30 d-flex align-items-center">
            <div class="product-img me-4">
                <a href="${product.link}">
                    <img src="${product.image}" alt="${product.name}">
                </a>
            </div>
            <div>
                <h5>${product.category || ''}</h5>
                <h4><a href="${product.link}">${product.name}</a></h4>
                <span class="fw-bold">${displayPrice}</span>
                ${originalPrice}
                <p>${product.description || ''}</p>
                <div class="product-action product-02-action">
                    <button class="action-btn wishlist-btn ${isWishlisted ? 'is-active' : ''}"
                            data-variant-id="${product.id}">
                        <i class="far fa-heart"></i>
                    </button>
                    <button class="c-btn add-to-cart-btn" data-variant-id="${product.id}">
                        Add to cart <i class="far fa-plus"></i>
                    </button>
                    <a class="action-btn" href="${product.link}">
                        <i class="far fa-search"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>`;
}

function render_pagination(pagination) {
    const $wrapper = $(".basic-pagination ul");
    $wrapper.empty();

    // Prev
    if (pagination.current_page > 1) {
        $wrapper.append(
            `<li><a href="#" class="page-link" data-page="${pagination.current_page - 1}">
                <i class="far fa-angle-left"></i>
             </a></li>`
        );
    }

    // Page numbers
    for (let i = 1; i <= pagination.last_page; i++) {
        const active = i === pagination.current_page ? "active" : "";
        $wrapper.append(
            `<li class="${active}">
                <a href="#" class="page-link" data-page="${i}">${i}</a>
             </li>`
        );
    }

    // Next
    if (pagination.current_page < pagination.last_page) {
        $wrapper.append(
            `<li><a href="#" class="page-link" data-page="${pagination.current_page + 1}">
                <i class="far fa-angle-right"></i>
             </a></li>`
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

$(document).on('click', '.wishlist-btn', function () {
    const $btn = $(this);
    const product_variant_id = $btn.data('variant-id') || null;

    $.post(`${appUrl}/customers/wishlist`, {
        product_variant_id, toggle: true
    }).done(function (res) {
        // Update wishlist counter somewhere in header
        $('body').find('#wishlist-count').text(res.wishlist.count);

        // Toggle UI
        $btn.toggleClass('is-active');
    }).fail(function () {
        alert('Unable to update wishlist.');
    });
});
