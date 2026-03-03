$(document).ready(function () {
  const $cardSection = $("#cardSection");
  const $paypalContainer = $("#paypal-button-container");
  const $placeOrderBtn = $("#place-order-button");
  const $form = $("#checkout-form");
  const $billingNewAddressSection = $("#billingNewAddressSection");
  const $toggleBillingFormBtn = $("#toggleBillingFormBtn");
  const $savedAddressInputs = $('input[name="saved_address_id"]');

  const $shippingSameAsBilling = $("#shipping_same_as_billing");
  const $shippingAddressSection = $("#shippingAddressSection");
  const $shippingProvince = $("#shipping-province-select");
  const $shippingCity = $("#shipping-city-select");

  const $shippingLat = $("#shipping_map_latitude");
  const $shippingLng = $("#shipping_map_longitude");
  const $shippingMapUrl = $("#shipping_map_url");


  $cardSection.hide();
  $paypalContainer.hide();
  $placeOrderBtn.hide();

  function togglePaymentSections() {
    const value = $('input[name="payment_method"]:checked').val();

    if (value === "stripe") {
      $cardSection.show();
      $paypalContainer.hide();
      $placeOrderBtn.show();
    } else if (value === "paypal") {
      $cardSection.hide();
      $paypalContainer.show();
      $placeOrderBtn.hide();
    } else if (value === "touras") {
      $cardSection.hide();
      $paypalContainer.hide();
      $placeOrderBtn.show();
    } else {
      $cardSection.hide();
      $paypalContainer.hide();
      $placeOrderBtn.show();
    }
  }

  $('input[name="payment_method"]').on("change", togglePaymentSections);
  togglePaymentSections();

  function setBillingFormState(show) {
    if (!$billingNewAddressSection.length) return;

    if (show) {
      $billingNewAddressSection.removeClass("d-none");
      $billingNewAddressSection.find("input, select, textarea").prop("disabled", false);
      if ($toggleBillingFormBtn.length) {
        $toggleBillingFormBtn.text("Hide New Address");
      }
    } else {
      $billingNewAddressSection.addClass("d-none");
      $billingNewAddressSection.find("input, select, textarea").prop("disabled", true);
      if ($toggleBillingFormBtn.length) {
        $toggleBillingFormBtn.text("Add New Address");
      }
    }
  }

  if ($savedAddressInputs.length) {
    const hasSelectedSavedAddress = $savedAddressInputs.filter(":checked").length > 0;
    if (hasSelectedSavedAddress) {
      setBillingFormState(false);
    } else {
      setBillingFormState(!$billingNewAddressSection.hasClass("d-none"));
    }

    $savedAddressInputs.on("change", function () {
      if ($(this).is(":checked")) {
        setBillingFormState(false);

        // Update map marker if address has coordinates
        const lat = $(this).data("lat");
        const lng = $(this).data("lng");
        if (lat && lng && typeof setMarker === "function") {
          setMarker(parseFloat(lat), parseFloat(lng));
        }
      }
    });

    if ($toggleBillingFormBtn.length) {
      $toggleBillingFormBtn.on("click", function () {
        const shouldShow = $billingNewAddressSection.hasClass("d-none");

        if (shouldShow) {
          $savedAddressInputs.prop("checked", false);
          setBillingFormState(true);
        } else {
          setBillingFormState(false);
        }
      });
    }
  }

  function toggleShippingSection() {
    if ($shippingSameAsBilling.is(":checked")) {
      $shippingAddressSection.addClass("d-none");
      $shippingAddressSection.find("input, select, textarea").prop("disabled", true);
    } else {
      $shippingAddressSection.removeClass("d-none");
      $shippingAddressSection.find("input, select, textarea").prop("disabled", false);
    }
  }

  $shippingSameAsBilling.on("change", toggleShippingSection);
  toggleShippingSection();

  function loadShippingCities(provinceId, selectedCity = "") {
    if (!provinceId) {
      $shippingCity.html('<option value="">Select shipping city</option>');
      return;
    }

    $shippingCity.html('<option value="">Loading...</option>');

    $.get(`${appUrl}/ajax/cities/${provinceId}`, function (data) {
      let options = '<option value="">Select shipping city</option>';
      data.forEach((city) => {
        const selected = String(selectedCity) === String(city.id) ? "selected" : "";
        options += `<option value="${city.id}" ${selected}>${city.name}</option>`;
      });
      $shippingCity.html(options);
    });
  }

  $shippingProvince.on("change", function () {
    loadShippingCities($(this).val());
  });

  const initialShippingProvince = $shippingProvince.val();
  const initialShippingCity = $shippingCity.data("old-city");
  if (initialShippingProvince) {
    loadShippingCities(initialShippingProvince, initialShippingCity);
  }

  function updateMapValues(lat, lng) {
    const fixedLat = Number(lat).toFixed(6);
    const fixedLng = Number(lng).toFixed(6);

    $shippingLat.val(fixedLat);
    $shippingLng.val(fixedLng);

    const mapUrl = `https://www.google.com/maps?q=${fixedLat},${fixedLng}`;
    $shippingMapUrl.val(mapUrl);

  }

  if (typeof L !== "undefined" && document.getElementById("shipping-map-picker")) {
    const defaultLat = parseFloat($shippingLat.val()) || 24.4539;
    const defaultLng = parseFloat($shippingLng.val()) || 54.3773;

    const map = L.map("shipping-map-picker").setView([defaultLat, defaultLng], 16);

    function autoLocate() {
      if (navigator.geolocation && !$shippingLat.val() && !$shippingLng.val() && !$savedAddressInputs.filter(":checked").length) {
        navigator.geolocation.getCurrentPosition(function (position) {
          setMarker(position.coords.latitude, position.coords.longitude);
        }, null, { enableHighAccuracy: true });
      }
    }

    setTimeout(autoLocate, 1000); // Small delay to ensure map is ready


    L.tileLayer("https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png", {
      maxZoom: 19,
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
      subdomains: 'abcd',
    }).addTo(map);

    let marker = null;

    function setMarker(lat, lng, panTo = true) {
      if (!marker) {
        marker = L.marker([lat, lng], { draggable: true }).addTo(map);
        marker.on("dragend", function (event) {
          const position = event.target.getLatLng();
          updateMapValues(position.lat, position.lng);
        });
      } else {
        marker.setLatLng([lat, lng]);
      }

      if (panTo) {
        map.setView([lat, lng], 12);
      }

      updateMapValues(lat, lng);
    }

    map.on("click", function (event) {
      setMarker(event.latlng.lat, event.latlng.lng);
    });

    if ($shippingLat.val() && $shippingLng.val()) {
      setMarker(parseFloat($shippingLat.val()), parseFloat($shippingLng.val()), false);
    }

    $("#use-current-location").on("click", function () {
      const $btn = $(this);
      const originalText = $btn.html();

      if (!navigator.geolocation) {
        Swal.fire({
          icon: "error",
          title: "Location Not Supported",
          text: "Your browser does not support location services.",
        });
        return;
      }

      $btn.prop("disabled", true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Locating...');

      navigator.geolocation.getCurrentPosition(
        function (position) {
          setMarker(position.coords.latitude, position.coords.longitude);
          $btn.prop("disabled", false).html(originalText);
        },
        function () {
          Swal.fire({
            icon: "error",
            title: "Location Error",
            text: "Unable to fetch your current location. Please drop a pin manually.",
          });
          $btn.prop("disabled", false).html(originalText);
        },
        {
          enableHighAccuracy: true,
          timeout: 10000,
          maximumAge: 0,
        }
      );
    });
  }

  $form.on("submit", async function (e) {
    if ($form.data("processing")) return;

    if (!$shippingLat.val() || !$shippingLng.val()) {
      e.preventDefault();
      Swal.fire({
        icon: "error",
        title: "Shipping Pin Required",
        text: "Please pin your shipping location on the map before placing the order.",
      });
      return;
    }

    const paymentMethod = $('input[name="payment_method"]:checked').val();
    if (paymentMethod === "paypal") return;

    e.preventDefault();
    $form.data("processing", true);

    const idbImages = await window.IDB.getAll();
    const formData = new FormData(this);

    if (idbImages && idbImages.length) {
      idbImages.forEach((item) => {
        if (item.files && item.files.length) {
          Array.from(item.files).forEach((file) => {
            formData.append(`customization_files[${item.id}][]`, file);
          });
        }
      });
    }

    $.ajax({
      url: $form.attr("action"),
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        if (response.redirect) {
          window.location.href = response.redirect;
          return;
        }

        if (response.clientSecret) {
          if (typeof confirmStripePayment === "function") {
            confirmStripePayment(response.clientSecret, response.order_id);
          } else {
            console.error("confirmStripePayment not found. Check stripe.js exposure.");
          }
          return;
        }

        console.log("Checkout response:", response);
        $form.data("processing", false);
      },
      error: function (xhr) {
        $form.data("processing", false);
        alert("Order failed: " + (xhr.responseJSON?.message || "Unknown error"));
      },
    });
  });
});
