$(document).ready(function () {
	$("#header").load("components/header-component.html"); // Load header
	$("#footer").load("components/footer-component.html"); // Load footer

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
					originalText.slice(0, i) + (i < originalText.length ? "|" : "")
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
