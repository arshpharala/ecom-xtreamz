// checkout.js
$(function () {
	// Go back button (simulate browser back)
	$("#goBackBtn").on("click", function (e) {
		e.preventDefault();
		window.history.back();
	});

	// Highlight selected card (if you add more saved cards in future)
	$('.form-check-input[type="radio"][name="savedCard"]').on(
		"change",
		function () {
			$(".saved-card-box").removeClass("selected");
			$(this)
				.closest(".form-check")
				.find(".saved-card-box")
				.addClass("selected");
		}
	);

	// Show card fields only if card payment is selected
	$('input[name="paymentType"]')
		.on("change", function () {
			if ($("#payCard").is(":checked")) {
				$("form")
					.find('input[placeholder="Card Number"]')
					.closest("form")
					.show();
			} else {
				$("form")
					.find('input[placeholder="Card Number"]')
					.closest("form")
					.hide();
			}
		})
		.trigger("change");
});
