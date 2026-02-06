/**
 * Upload.js
 * Handles image customization upload, preview, and deletion
 */

$(document).ready(function () {
  console.log("Upload.js loaded");

  // Ensure globally available for cart.js
  window.customizationImages = window.customizationImages || [];

  // Toggle Customization Section
  $(document).on("change", "#customizationEnabled", function () {
    const isChecked = $(this).is(":checked");
    console.log("Branding Options toggled:", isChecked);
    if (isChecked) {
      $("#customizationFields").slideDown(150);
    } else {
      $("#customizationFields").slideUp(150);
    }
  });

  // Render Preview
  function renderCustomizationPreview() {
    const $previewContainer = $("#customizationPreview");
    if (!$previewContainer.length) return;

    $previewContainer.empty();

    if (!window.customizationImages || !Array.isArray(window.customizationImages)) {
      window.customizationImages = [];
    }

    window.customizationImages.forEach((item, index) => {
      const $item = $("<div>").addClass("position-relative");

      const $img = $("<img>")
        .attr({
          src: item.url,
          alt: "Branding asset " + (index + 1),
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
          transform: "translate(30%, -30%)",
          zIndex: 10
        })
        .html("&times;"); // Proper HTML checkmark/x

      $item.append($img, $btn);
      $previewContainer.append($item);
    });

    // Update file input label or status if needed
    console.log("Current images:", window.customizationImages.length);
  }

  // Handle File Selection
  $(document).on("change", "#customizationImages", function () {
    console.log("File input changed");

    // Handle single file selection (one by one)
    const file = this.files && this.files[0] ? this.files[0] : null;

    if (!file) {
      console.log("No file selected");
      return;
    }

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

    console.log("Adding file:", file.name);
    const url = URL.createObjectURL(file);
    window.customizationImages.push({ file, url });
    renderCustomizationPreview();

    // Reset input to allow selecting the same file again or a new one
    $(this).val("");
  });

  // Handle Remove Button
  $(document).on("click", ".customization-remove", function (e) {
    e.preventDefault(); // Prevent accidental form submission
    const index = parseInt($(this).data("index"), 10);
    console.log("Removing image at index:", index);

    if (isNaN(index)) return;

    const item = window.customizationImages[index];
    if (item && item.url) {
      URL.revokeObjectURL(item.url);
    }

    window.customizationImages.splice(index, 1);
    renderCustomizationPreview();
  });

  // Initial render if any (e.g. from previous state if persisted, though unlikely)
  renderCustomizationPreview();
});
