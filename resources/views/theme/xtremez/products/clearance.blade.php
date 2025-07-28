@extends('theme.xtremez.layouts.app')

@section('breadcrumb')
  <section class="breadcrumb-bar py-2">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 py-2">
          <li class="breadcrumb-item">
            <a href="{{ route('home') }}" class="text-white" title="Home">
              <!-- <i class="bi bi-house"></i> -->
              Home
            </a>
          </li>
          <li class="breadcrumb-item active text-white" aria-current="page" title="{{ $page->title ?? 'Clearance' }}">
            {{ $page->title ?? 'Page' }}
          </li>
        </ol>
      </nav>
    </div>
  </section>
@endsection
@section('content')
  <section class="heading-section py-5">
    <div class="container">
      <div class="heading-row">
        <h2 class="section-title fs-1 text-center m-0">Clearance</h2>
      </div>
    </div>
  </section>


  <section class="product-section pb-5">
    <div class="container">
      <div class="row g-4" id="clearance-products">

      </div>

      <!-- Pagination -->
      <nav class="d-flex justify-content-center my-5 pagination-section">
        <ul class="pagination mb-0">

        </ul>
      </nav>
    </div>
  </section>
@endsection

@push('scripts')
  <script>
    $(document).ready(function() {

      searchProducts();

      function searchProducts(page = 1) {
        $.ajax({
          url: "{{ route('ajax.get-products') }}",
          method: "GET",
          data: {
            page: page,
            offer: 1
          },
          dataType: "json",
          success: function(response) {
            if (response.success && response.data?.products?.length) {
              const html = response.data.products.map((product) =>
                render_product_card(product, "col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3")
              );

              console.log(html);

              $("#clearance-products").html(html);

              render_pagination(response.data.pagination);

            } else {
              $("#clearance-products").html(`<p class="text-muted">No products found.</p>`);
            }
          },
          error: function() {
            console.error("Failed to load featured products");
            $("#clearance-products").html(`<p class="text-danger">Error loading products.</p>`);
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
@endpush
