/**
 * Upload.js
 * Handles image customization upload, preview, and deletion
 */

$(function () {
  const $customizationToggle = $("#customizationEnabled");
  const $customizationFields = $("#customizationFields");
  const $customizationImages = $("#customizationImages");
  const $customizationPreview = $("#customizationPreview");

  // Ensure globally available for cart.js
  window.customizationImages = window.customizationImages || [];

  if ($customizationToggle.length) {
    $customizationToggle.on("change", function () {
      if ($(this).is(":checked")) {
        $customizationFields.slideDown(150);
      } else {
        $customizationFields.slideUp(150);
      }
    });
  }

  function renderCustomizationPreview() {
    if (!$customizationPreview.length) return;

    $customizationPreview.empty();

    window.customizationImages.forEach((item, index) => {
      const $item = $("<div>").addClass("position-relative");
      const $img = $("<img>")
        .attr({
          src: item.url,
          alt: "Customization image",
        })
        .addClass("img-thumbnail")
        .css({
          width: "80px",
          height: "80px",
          objectFit: "cover",
        });

      const $btn = $("<button>")
        .attr({
          type: "button",
          "data-index": index,
        })
        .addClass("btn btn-sm btn-danger position-absolute top-0 end-0 p-0 d-flex align-items-center justify-content-center customization-remove")
        .css({
          width: "20px",
          height: "20px",
          borderRadius: "50%",
          transform: "translate(30%, -30%)"
        })
        .text("x");

      $item.append($img, $btn);
      $customizationPreview.append($item);
    });
  }

  if ($customizationImages.length) {
    $customizationImages.on("change", function () {
      // Handle single file selection (one by one)
      const file = this.files && this.files[0] ? this.files[0] : null;

      if (!file) return;

      if (window.customizationImages.length >= 5) {
        alert("You can upload up to 5 images only.");
        $(this).val("");
        return;
      }

      if (!file.type || !file.type.startsWith("image/")) {
        alert("Please select a valid image file.");
        $(this).val("");
        return;
      }

      const url = URL.createObjectURL(file);
      window.customizationImages.push({ file, url });
      renderCustomizationPreview();

      // Reset input to allow selecting the same file again or a new one
      $(this).val("");
    });
  }

  $(document).on("click", ".customization-remove", function () {
    const index = parseInt($(this).data("index"), 10);
    if (isNaN(index)) return;

    const item = window.customizationImages[index];
    if (item && item.url) {
      URL.revokeObjectURL(item.url);
    }

    window.customizationImages.splice(index, 1);
    renderCustomizationPreview();
  });
});
