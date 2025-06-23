// cart.js
$(function () {
  // Select All checkbox functionality
  $('#selectAll').on('change', function () {
    const checked = $(this).is(':checked');
    $('.cart-item:visible .form-check-input').prop('checked', checked);
  });

  // Individual checkbox syncs Select All
  $('.cart-items').on('change', '.form-check-input', function () {
    // Only checkboxes visible (i.e., desktop)
    const $checkboxes = $('.cart-item:visible .form-check-input');
    const total = $checkboxes.length;
    const checked = $checkboxes.filter(':checked').length;
    $('#selectAll').prop('checked', checked === total);
  });

  // Quantity Plus/Minus functionality
  $('.cart-items').on('click', '.qty-btn.plus', function () {
    // find the .cart-qty-val in the same cart item
    let $qtyBox = $(this).closest('.cart-item').find('.cart-qty-val').first();
    let qty = parseInt($qtyBox.text(), 10);
    qty = isNaN(qty) ? 1 : qty + 1;
    $qtyBox.text(qty);
  });

  $('.cart-items').on('click', '.qty-btn.minus', function () {
    let $qtyBox = $(this).closest('.cart-item').find('.cart-qty-val').first();
    let qty = parseInt($qtyBox.text(), 10);
    if (!isNaN(qty) && qty > 1) {
      $qtyBox.text(qty - 1);
    }
  });

  // Trash button: Remove item from cart (optional)
  $('.cart-items').on('click', '.btn-trash', function () {
    $(this).closest('.cart-item').remove();
    // Re-calculate Select All after remove
    const $checkboxes = $('.cart-item:visible .form-check-input');
    const total = $checkboxes.length;
    const checked = $checkboxes.filter(':checked').length;
    $('#selectAll').prop('checked', checked === total);
  });
});
