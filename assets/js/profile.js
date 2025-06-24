// Description: JavaScript for handling profile edit functionality
$(function () {
	$(".btn-edit").on("click", function () {
		const $btn = $(this);
		const $input = $btn.prev();

		if ($input.prop("readonly")) {
			$input.prop("readonly", false).focus();
			$btn.html('<i class="bi bi-check-lg"></i>');
		} else {
			$input.prop("readonly", true);
			$btn.html('<i class="bi bi-pencil"></i>');
		}
	});

	$(".gender-toggle input[type=radio]").on("change", function () {
		var $group = $(this).closest(".gender-toggle");
		$group.find("label").removeClass("active");
		$group.find('label[for="' + this.id + '"]').addClass("active");
	});

	$(".gender-toggle label").on("click", function () {
		var $group = $(this).closest(".gender-toggle");
		$group.find("label").removeClass("active");
		$(this).addClass("active");
	});
});
