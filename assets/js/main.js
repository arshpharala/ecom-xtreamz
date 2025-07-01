$(document).ready(function () {
	$("#header").load("components/header-component.html"); // Load header
	$("#footer").load("components/footer-component.html"); // Load footer

	$("body").on("click", "#mobileNavToggle", function () {
		$("#mobileNav").collapse("toggle");
	});
});

$(".btn-primary").on("click", function (e) {
	e.preventDefault();
	$("html, body").animate(
		{
			scrollTop: $("#product-section").offset().top,
		},
		600
	);

	$(".add-to-cart-btn").on("click", function () {
		var productId = $(this).data("product-id");
		// You can trigger AJAX here if needed
		$(this).text("Added!").removeClass("btn-primary").addClass("btn-success");

		setTimeout(() => {
			$(this)
				.text("Add to Cart")
				.removeClass("btn-success")
				.addClass("btn-primary");
		}, 1500);
	});
});

// Sticky Header

const header = document.querySelector("#header");
const toggleClass = "is-sticky";

window.addEventListener("scroll", () => {
	const currentScroll = window.pageYOffset;
	if (currentScroll > 150) {
		header.classList.add(toggleClass);
	} else {
		header.classList.remove(toggleClass);
	}
});
