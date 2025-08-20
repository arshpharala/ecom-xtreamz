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
            <div class="input-group search-group">
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
          <select class="form-select theme-select border-0 sort-select" name="sort_by">
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
          <div class="sidebar-filters pe-4">
            <!-- Category -->
            <div class="mb-4">
              <ul class="category-list list-unstyled mb-0">
                @foreach ($categories as $category)
                  <li
                    class="d-flex align-items-center py-3 border-bottom {{ $category->id == $activeCategory->id ? 'active' : '' }}    "
                    data-category="{{ $category->id }}">
                    <img src="{{ asset('storage/' . $category->icon) }}" class="me-2" width="22" alt>
                    {{ $category->name }} <span class="ms-auto badge text-dark">{{ $category->products_count }}</span>
                  </li>
                @endforeach
              </ul>
            </div>

            <!-- Brands -->
            <div class="mb-4">
              <h5 class="fs-3 mb-3">Brands</h5>
              <select class="form-select theme-select" name="brand_id">
                <option value="" selected>–</option>
                @foreach ($brands as $brand)
                  <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                @endforeach
              </select>
            </div>

            <div id="dynamic-attribute-filters">

            </div>


            <!-- Tags -->
            <div class="mb-4">
              <h5 class="fs-3 mb-3x ">Tags</h5>
              @foreach ($tags as $tag)
              <div class="form-check">
                <input class="form-check-input cc-form-check-input" type="checkbox" id="tag_{{ $tag->id }}">
                <label class="form-check-label" for="tag_{{ $tag->id }}">{{ $tag->name }}</label>
              </div>
              @endforeach
            </div>

            <!-- Price Range -->
            <div class="price-range-wrapper cc-price-range mb-4">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span id="priceLabelMinSidebar" class="price-label">{!! price_format(active_currency(), 10) !!}</span>
                <span id="priceLabelMaxSidebar" class="price-label">{!! price_format(active_currency(), 2000) !!}</span>
              </div>
              <div id="price-slider-sidebar"></div>
            </div>

          </div>
        </aside>

        <!-- Main Content: Product List -->
        <main class="col-lg-9 col-md-8">

          <!-- Product Grid -->
          <div class="row g-4" id="products">
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
