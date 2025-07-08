$(function () {
	const $mainImage = $("#zoomImage");
	const thumbItemWidth = $(".thumb-item").outerWidth(true);
	const $thumbWrapper = $(".thumb-wrapper");
	const $prevBtn = $("#thumbPrev");
	const $nextBtn = $("#thumbNext");
	const visibleCount = 4;

	function updateThumbNav() {
		const scrollLeft = $thumbWrapper.scrollLeft();
		const maxScroll = $thumbWrapper[0].scrollWidth - $thumbWrapper.outerWidth();

		const hasOverflow = $thumbWrapper.children().length > visibleCount;
		$prevBtn.toggle(hasOverflow && scrollLeft > 5);
		$nextBtn.toggle(hasOverflow && scrollLeft < maxScroll - 5);
	}

	function getThumbWidth() {
		const $first = $thumbWrapper.find(".thumb-item").first();
		return $first.outerWidth(true);
	}

	$nextBtn.on("click", () => {
		const w = getThumbWidth();
		$thumbWrapper.animate({ scrollLeft: "+=" + w }, 200, updateThumbNav);
	});

	$prevBtn.on("click", () => {
		const w = getThumbWidth();
		$thumbWrapper.animate({ scrollLeft: "-=" + w }, 200, updateThumbNav);
	});

	$thumbWrapper.on("scroll resize", updateThumbNav);
	$(window).on("resize", updateThumbNav);
	updateThumbNav();

	// Swap main image on thumbnail click
	$(".thumb-item").on("click", function () {
		const largeImg = $(this).data("large");
		$(".thumb-item").removeClass("active");
		$(this).addClass("active");

		$mainImage.attr("src", largeImg).attr("data-zoom-image", largeImg);
	});

	// Recalculate on resize
	$(window).on("resize", updateThumbNav);
	updateThumbNav();

	// Quantity controls
	$("#qtyPlus").click(() => {
		$("#qtyInput").val((i, val) => +val + 1);
	});
	$("#qtyMinus").click(() => {
		$("#qtyInput").val((i, val) => (val > 1 ? +val - 1 : 1));
	});

	// Color swatch active toggle
	$(".color-swatch").on("click", function () {
		$(".color-swatch").removeClass("active");
		$(this).addClass("active");
	});
});
