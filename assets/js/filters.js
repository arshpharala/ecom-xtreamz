$(function () {
  var $priceSlider = $('#price-slider')[0];
  var $labelMin = $('#priceLabelMin');
  var $labelMax = $('#priceLabelMax');

  noUiSlider.create($priceSlider, {
    start: [200, 2000],
    connect: true,
    range: { min: 5, max: 4000 },
    step: 5,
    format: {
      to: value => Math.round(value),
      from: value => Number(value)
    }
  });

  // Update labels on slider move
  $priceSlider.noUiSlider.on('update', function(values) {
    $labelMin.text(values[0] + ' AED');
    $labelMax.text(values[1] + ' AED');
  });
});
