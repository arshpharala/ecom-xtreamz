function fetchProducts(type, limit, url) {
    url = url || window.ajaxProductURL;

    return $.get(url, { type: type, limit: limit })
        .then(function (res) {
            if (
                res &&
                res.success &&
                res.data &&
                Array.isArray(res.data.products)
            ) {
                return res.data.products;
            }
            return [];
        })
        .catch(function () {
            return [];
        });
}

// ----------------------------
// Carousel init + render
// ----------------------------
(function ($, w, d) {
    function owlConfig(items) {
        return {
            loop: false,
            margin: 24,
            nav: false,
            dots: false,
            autoHeight: false,
            items: items || 4,
            responsive: {
                0: { items: 1 },
                576: { items: 1 },
                768: { items: 2 },
                992: { items: Math.min(items || 4, 3) },
                1200: { items: items || 4 },
            },
        };
    }

    function ensureOwl($owl, items) {
        if ($owl.hasClass("owl-loaded")) {
            $owl.trigger("destroy.owl.carousel");
            $owl.removeClass("owl-loaded");
            $owl.find(".owl-stage-outer").children().unwrap();
        }
        $owl.owlCarousel(owlConfig(items));
    }

    function showSkeleton($owl, count) {
        var cards = Array.from(
            { length: Math.min(count || 4, 4) },
            function () {
                return (
                    '<div class="skeleton-card">' +
                    '<div class="sk-img"></div>' +
                    '<div class="sk-line sk-1"></div>' +
                    '<div class="sk-line sk-2"></div>' +
                    "</div>"
                );
            }
        );
        $owl.trigger("replace.owl.carousel", [cards.join("")]).trigger(
            "refresh.owl.carousel"
        );
    }

    function emptyStateHTML() {
        return '<div class="text-center py-5 w-100">No products to show.</div>';
    }

    function initAjaxCarousel($wrap) {
        var $owl = $wrap.find(".owl-carousel");
        var type = $wrap.data("type");
        var limit = parseInt($wrap.data("limit"), 10) || 8;
        var items = parseInt($wrap.data("items"), 10) || 4;
        var url = $wrap.data("url") || w.ajaxProductURL;
        var prevSel = $wrap.data("prev");
        var nextSel = $wrap.data("next");

        // Ensure your render_product_card exists
        if (typeof w.render_product_card !== "function") {
            console.error("render_product_card function is not defined.");
            return;
        }

        ensureOwl($owl, items);
        showSkeleton($owl, items);

        // Bind external controls (unique namespace per section)
        var ns =
            ".oc" + ($wrap.attr("id") || Math.random().toString(36).slice(2));
        if (prevSel)
            $(prevSel)
                .off(ns)
                .on("click" + ns, function (e) {
                    e.preventDefault();
                    $owl.trigger("prev.owl.carousel");
                });
        if (nextSel)
            $(nextSel)
                .off(ns)
                .on("click" + ns, function (e) {
                    e.preventDefault();
                    $owl.trigger("next.owl.carousel");
                });

        fetchProducts(type, limit, url).then(function (products) {
            var html = products.length
                ? products
                      .map(function (p) {
                          return w.render_product_card(p);
                      })
                      .join("")
                : emptyStateHTML();

            $owl.trigger("replace.owl.carousel", [html]).trigger(
                "refresh.owl.carousel"
            );
        });
    }

    function initAllAjaxCarousels(selector) {
        selector = selector || ".ajax-carousel";
        $(selector).each(function () {
            initAjaxCarousel($(this));
        });
    }

    // Public API
    w.AjaxCarousels = {
        initOne: initAjaxCarousel,
        initAll: initAllAjaxCarousels,
        reload: function (selectorOrEl) {
            var $wrap = $(selectorOrEl);
            if ($wrap.length) initAjaxCarousel($wrap);
        },
    };

    // Auto-init
    $(function () {
        initAllAjaxCarousels();
    });
})(jQuery, window, document);
