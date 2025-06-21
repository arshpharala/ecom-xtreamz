function initPriceSlider(sliderId, labelMinId, labelMaxId, startMin = 200, startMax = 2000) {
  var $slider = document.getElementById(sliderId);
  var $labelMin = $("#" + labelMinId);
  var $labelMax = $("#" + labelMaxId);

  if (!$slider) return;

  // Destroy previous slider if exists
  if ($slider.noUiSlider) {
    $slider.noUiSlider.destroy();
  }

  noUiSlider.create($slider, {
    start: [startMin, startMax],
    connect: true,
    range: { min: 5, max: 4000 },
    step: 5,
    format: {
      to: value => Math.round(value),
      from: value => Number(value)
    }
  });

  $slider.noUiSlider.on("update", function (values) {
    $labelMin.text(values[0] + " AED");
    $labelMax.text(values[1] + " AED");
  });
}

$(function () {
  // Initialize sidebar slider immediately
  initPriceSlider("price-slider-sidebar", "priceLabelMinSidebar", "priceLabelMaxSidebar");

  // Initialize/re-init modal slider on open
  $("#openFilterModal").on("click", function () {
    var modal = new bootstrap.Modal(document.getElementById("filterModal"));
    modal.show();

    setTimeout(function () {
      initPriceSlider("price-slider-modal", "priceLabelMinModal", "priceLabelMaxModal");
    }, 300);
  });
});
