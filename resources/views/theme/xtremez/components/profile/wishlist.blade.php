<div id="wishlist" class="profile-tab">
  <div class="profile-main bg-white p-4 border border-2 shadow">
    <div class="row d-flex" id="wishlist-products">

    </div>

    <!-- Pagination -->
    <nav class="d-flex justify-content-center my-5 pagination-section">
      <ul class="pagination mb-0">

      </ul>
    </nav>
    <div class="order-item text-center py-5 text-muted" id="no-product" style="display: none">
      No products found in your wishlist.
    </div>
  </div>

</div>

<script>
  $(document).ready(function() {

    searchProducts();

    function searchProducts(page = 1) {
      $.ajax({
        url: "{{ route('ajax.get-products') }}",
        method: "GET",
        data: {
          is_wishlisted: 1,
          page: page,
          per_page: 4
        },
        dataType: "json",
        success: function(response) {
          if (response.success && response.data?.products?.length) {
            const html = response.data.products.map((product) =>
              render_product_card(product, "col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6")
            );

            $('#whislist').find("#wishlist-products").html(html);

            render_pagination(response.data.pagination);

          } else {
            $("#no-product").show();
          }
        },
        error: function() {
          console.error("Failed to load featured products");
          $("#wishlist-products").html(`<p class="text-danger">Error loading products.</p>`);
        },
      });
    }


    $(document).on("click", ".pagination .page-link", function(e) {
      e.preventDefault();
      const page = $(this).data("page");
      if (page) searchProducts(page);
    });

  });
</script>
