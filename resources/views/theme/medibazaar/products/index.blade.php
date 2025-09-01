@extends('theme.medibazaar.layouts.app')
@section('content')
    <!-- breadcrumb-area-start -->
    <div class="breadcrumb-area pt-125 pb-125" style="background-image:url({{ asset('storage/' . $page->banner) }})">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="breadcrumb-wrapper">
                        <div class="breadcrumb-text">
                            <h2>{{ $page->translation?->title ?? 'Product' }}</h2>
                        </div>
                        {{-- <ul class="breadcrumb-menu">
                            <li><a href="index.html">home</a></li>
                            <li><span>shop</span></li>
                        </ul> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb-area-end -->


    <!-- shop-full-area-start -->
    <div class="shhop-full-area pt-100 pb-100 pr-60 pl-60">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-3 custom-col-2">
                    <div class="category-sidebar cat-side mb-30">
                        <h3 class="cat-title">Category</h3>
                        <div class="category-item">
                            <ul>
                                @foreach ($categories as $category)
                                    <li><a href="#" class="{{ $category->id == $activeCategory->id ? 'active' : '' }}"
                                            data-category="{{ $category->id }}">{{ $category->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="category-sidebar cat-side mb-30">
                        <h3 class="cat-title">Filter By Color</h3>
                        <div class="cat-widget">
                            <div class="cat-widget-color">
                                <span class="c-1"></span>
                                <span class="c-2"></span>
                                <span class="c-3"></span>
                                <span class="c-4"></span>
                                <span class="c-5"></span>
                                <span class="c-6"></span>
                                <span class="c-7"></span>
                                <span class="c-8"></span>
                                <span class="c-9"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-9">
                    <div class="pro-ful-tab">
                        <div class="row mb-20">
                            <div class="col-xl-4 col-lg-3 col-md-3">
                                <div class="product-02-tab mb-20">
                                    <ul class="nav justify-content-start product-nav" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                                role="tab" aria-controls="home" aria-selected="true">
                                                <i class="fas fa-th-large"></i>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile"
                                                role="tab" aria-controls="profile" aria-selected="false">
                                                <i class="fas fa-bars"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-5 col-md-5">
                                <div class="pro-tab1-content pt-15 mb-20 text-md-center">
                                    <h4>Showing 20 Results Of 50 Products</h4>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <div class="pro-filter mb-20 f-right">
                                    <form action="#">
                                        <select name="pro-filter" id="pro-filter">
                                            <option value="1">Default Type </option>
                                            <option value="2">Top Sales </option>
                                            <option value="3">New Product </option>
                                            <option value="4">A to Z Product </option>
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="product-tab-content">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="home" role="tabpanel"
                                    aria-labelledby="home-tab">
                                    <div class="row" id="products"><!-- AJAX products injected here --></div>
                                </div>
                                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                    <div class="row" id="products-list"><!-- AJAX list view injected here --></div>
                                </div>
                            </div>
                        </div>

                        <div class="basic-pagination mt-20 basic-pagination-2 text-center">
                            <ul>
                                <li><a href="#"><i class="far fa-angle-left"></i></a></li>
                                <li class="active"><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#"><i class="far fa-angle-right"></i></a></li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- shop-full-area-area -->
@endsection
@push('scripts')
    <script>
        // Laravel route passed to JS
        window.ajaxProductURL = "{{ route('ajax.get-products') }}";
        window.activeCategoryId = "{{ $activeCategory->id ?? '' }}";
    </script>

    <script></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"></script>
    <script src="{{ asset('theme/medibazaar/assets/js/filters.js') }}"></script>
@endpush
