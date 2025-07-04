// cart.js
$(function () {
	// Select All checkbox functionality
	$("#selectAll").on("change", function () {
		const checked = $(this).is(":checked");
		$(".cart-item:visible .form-check-input").prop("checked", checked);
	});

	// Individual checkbox syncs Select All
	$(".cart-items").on("change", ".form-check-input", function () {
		// Only checkboxes visible (i.e., desktop)
		const $checkboxes = $(".cart-item:visible .form-check-input");
		const total = $checkboxes.length;
		const checked = $checkboxes.filter(":checked").length;
		$("#selectAll").prop("checked", checked === total);
	});

	// Quantity Plus
	$(".cart-items").on("click", ".qty-btn.plus", function () {
		let $cartItem = $(this).closest(".cart-item");
		let $qtyBoxes = $cartItem.find(".cart-qty-val");
		let qty = parseInt($qtyBoxes.first().text(), 10);
		qty = isNaN(qty) ? 1 : qty + 1;
		$qtyBoxes.text(qty); // update all qty boxes in this cart item
	});

	// Quantity Minus
	$(".cart-items").on("click", ".qty-btn.minus", function () {
		let $cartItem = $(this).closest(".cart-item");
		let $qtyBoxes = $cartItem.find(".cart-qty-val");
		let qty = parseInt($qtyBoxes.first().text(), 10);
		if (!isNaN(qty) && qty > 1) {
			$qtyBoxes.text(qty - 1); // update all qty boxes in this cart item
		}
	});

	// Trash button: Remove item from cart (optional)
	$(".cart-items").on("click", ".btn-trash", function () {
		$(this).closest(".cart-item").remove();
		// Re-calculate Select All after remove
		const $checkboxes = $(".cart-item:visible .form-check-input");
		const total = $checkboxes.length;
		const checked = $checkboxes.filter(":checked").length;
		$("#selectAll").prop("checked", checked === total);
	});
});
