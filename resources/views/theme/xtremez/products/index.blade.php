@extends('theme.xtremez.layouts.app')
@push('head')
  <!-- noUiSlider CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css" rel="stylesheet">
@endpush
@section('breadcrumb')
  <section class="breadcrumb-bar py-2">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 py-2">
          <li class="breadcrumb-item">
            <a href="#" class="text-white" title="Home">
              <!-- <i class="bi bi-house"></i> -->
              Home
            </a>
          </li>
          <li class="breadcrumb-item active text-white" aria-current="page" title="Categories">
            Categories
          </li>
        </ol>
      </nav>
    </div>
  </section>
@endsection
@section('content')
  <section class="listing-header py-5">
    <div class="container">
      <div class="row align-items-center g-3">
        <div class="col-md-3 col-xl-3 col-lg-3 d-flex align-items-center">
          <h2 class="section-title fw-normal mb-0" style="font-size:2.3rem;">Category</h2>
        </div>
        <div class="col-md-5 col-lg-5 col-xl-6 d-flex align-items-center">
          <form class="cc-search-bar w-100">
            <div class="input-group search-group border">
              <input type="text" class="form-control search-input fw-bold type-placeholder"
                placeholder="Search Here" />
              <span class="input-group-text search-icon">
                <i class="bi bi-search"></i>
              </span>
            </div>
          </form>
          <!-- Filter Icon (shows only on mobile) -->
          <button class="btn filter-btn d-md-none ms-2" id="openFilterModal" aria-label="Open Filters">
            <i class="bi bi-funnel-fill fs-2"></i>
          </button>
        </div>
        <div class="col-md-4 col-lg-3  col-xl-3 d-none d-md-flex align-items-center justify-content-end gap-3">
          <span class="fw-bold sort-label text-nowrap">Sort By</span>
          <select class="form-select theme-select sort-select" name="sort_by">
            <option value="">Featured</option>
            <option value="price_asc">Price: Low to High</option>
            <option value="price_desc">Price: High to Low</option>
            <option value="newest">Newest</option>
          </select>
        </div>
      </div>
    </div>
  </section>

  <section class="product-listing-page py-2">
    <div class="container">

      <div class="row">
        <!-- Sidebar: Filters -->
        <aside class="col-lg-3 col-md-4 mb-4 d-none d-md-block">

          @include('theme.xtremez.components.products.filters')

        </aside>

        <!-- Main Content: Product List -->
        <main class="col-lg-9 col-md-8">

          <!-- Product Grid -->
          <div class="row g-4" id="products" style="display: none;">
          </div>

          <div class="row" id="loader"
            style="display: none; height: 50vh; justify-content: center; align-items: center; background: none;">
            <div class="col-md-4 text-center">
              <div class="spinner-border" style="width: 4rem; height: 4rem;" role="status">
                <span class="sr-only"></span>
              </div>
            </div>
          </div>

          <div class="row" id="no-products"
            style="display: none; height: 50vh; justify-content: center; align-items: center; background: none;">
            <div class="col-md-4 text-center">
              <img src="{{ asset('theme/xtremez/assets/images/no-product.png') }}" alt="No Products"
                style="max-height: 80vh; max-width: 100%; object-fit: contain; background: none;">
            </div>
          </div>



          <!-- Pagination -->
          <nav class="d-flex justify-content-center my-5 pagination-section">
            <ul class="pagination mb-0">

            </ul>
          </nav>
        </main>
      </div>
    </div>
  </section>

  <!-- Filter Modal / Offcanvas -->
  <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout modal-fullscreen-sm-down">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="filterModalLabel">Filters</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          @include('theme.xtremez.components.products.filters')
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Apply Filters</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    // Laravel route passed to JS
    window.ajaxProductURL = "{{ route('ajax.get-products') }}";
    window.activeCategoryId = "{{ $activeCategory->id ?? '' }}";
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"></script>
  <script src="{{ asset('theme/xtremez/assets/js/filters.js') }}"></script>
@endpush
