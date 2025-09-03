@extends('theme.medibazaar.layouts.app')
@section('content')
    <!-- breadcrumb-area-start -->
    {{-- <div class="breadcrumb-area pt-125 pb-125" style="background-image:url({{ asset('storage/' . $page->banner) }})">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="breadcrumb-wrapper">
                        <div class="breadcrumb-text">
                            <h2>product details</h2>
                        </div>
                        <ul class="breadcrumb-menu">
                            <li><a href="index.html">home</a></li>
                            <li><span>product details</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb-area-end --> --}}

    <!-- shop-banner-area start -->
    <section class="shop-banner-area pt-100 pb-70">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6">
                    <div class="product-details-img mb-30">
                        <div class="tab-content" id="myTabContent2">
                            @foreach ($productVariant->attachments as $image)
                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $image->id }}"
                                    role="tabpanel">
                                    <div class="product-large-img">
                                        <img src="{{ asset('storage/' . $image->file_path) }}" alt="">
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>

                    <div class="shop-thumb-tab">
                        <ul class="nav" id="myTab2" role="tablist">
                            @foreach ($productVariant->attachments as $image)
                                <li class="nav-item">
                                    <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ $image->id }}"
                                        data-toggle="tab" href="#{{ $image->id }}" role="tab"
                                        aria-selected="true"><img src="{{ asset('storage/' . $image->file_path) }}"
                                            alt=""> </a>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6">
                    <div class="product-details-wrapper mb-30">
                        <div class="product-text">
                            <h5>{{ $productVariant->category_name }}</h5>
                            <h4>{{ $productVariant->name }}</h4>
                            {{-- <div class="pro-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fad fa-star-half-alt"></i>
                                <span>(05 Reviews)</span>
                            </div> --}}
                            <span>{!! $productVariant->price_with_currency !!}</span>
                        </div>
                        <div class="product-variant">
                            <div class="product-desc">
                                <p>{{ $productVariant->description }}</p>
                            </div>
                            <!-- Dynamic Attributes -->
                            @foreach ($attributes as $slug => $attr)
                                <div class="product-info-list d-flex align-items-center" data-attr="{{ $slug }}">
                                    <span>{{ $attr['name'] }}</span>
                                    @foreach ($attr['values'] as $val)
                                        @php
                                            $isActive = isset($selected[$slug]) && $selected[$slug] === $val;
                                            $isColor = Str::lower($attr['name']) === 'color';
                                        @endphp
                                        <a href="javascript:void(0)"
                                            class="variant-option {{ $isColor ? 'color-swatch' : 'option-box' }} {{ $isActive ? 'active' : '' }}"
                                            data-attr="{{ $slug }}" data-value="{{ $val }}"
                                            @if ($isColor) style="background: {{ $val }}" @endif>
                                            @unless ($isColor)
                                                {{ $val }}
                                            @endunless
                                        </a>
                                    @endforeach
                                </div>
                            @endforeach

                            <span class="stock mt-2">{{ $productVariant->stock }} In Stock</span>

                            <!-- Qty + Cart -->
                            <div class="product-action-details">
                                <div class="d-flex gap-3 py-3">
                                    <div class="plus-minus">
                                        <div class="cart-plus-minus">
                                            <input type="text" id="qtyInput"
                                                value="{{ $productVariant->cart_item['qty'] ?? 1 }}">
                                            <div class="dec qtybutton">-</div>
                                            <div class="inc qtybutton">+</div>
                                        </div>
                                    </div>
                                    <div>
                                        <button class="c-btn red-btn add-to-cart-btn"
                                            data-variant-id="{{ $productVariant->id }}" data-qty-selector="#qtyInput">
                                            Add to Cart <i class="far fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="product-02-list">
                                <ul>
                                    <li>
                                        <div class="pro-02-list-info f-left">
                                            <span>SKU :</span>
                                        </div>
                                        <div class="pro-02-list-text">
                                            <span>{{ $productVariant->sku }}</span>
                                        </div>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- shop-banner-area end -->

    <!-- product-desc-area start -->
    <section class="product-desc-area pb-60">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 mb-30">
                    <div class="bakix-details-tab">
                        <ul class="nav text-center pb-30 mb-50" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="desc-tab" data-toggle="tab" href="#id-desc" role="tab"
                                    aria-controls="home" aria-selected="true">Description </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="id-add-in" data-toggle="tab" href="#id-add" role="tab"
                                    aria-controls="profile" aria-selected="false">Additional Information</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="id-desc" role="tabpanel" aria-labelledby="desc-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="event-text">
                                        @if (empty($productVariant->description))
                                            <p>No description available.</p>
                                        @else
                                            {!! $productVariant->description !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="id-add" role="tabpanel" aria-labelledby="id-add-in">
                            <div class="additional-info">
                                <div class="table-responsive">
                                    <h4>Additional information</h4>
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <th>Brand</th>
                                                <td class="product_dimensions">{{ $productVariant->brand_name ?? 'N/A' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Weight</th>
                                                <td class="product_weight">{{ $productVariant->shipping?->weight }} kgs
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Dimensions</th>
                                                <td class="product_dimensions">{{ $productVariant->shipping?->length }} x
                                                    {{ $productVariant->shipping?->width }} x
                                                    {{ $productVariant->shipping?->height }} cm</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- product-desc-area end -->

    <!-- product-area-start -->
    <div class="product-area pb-100">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 offset-lg-3 offset-xl-3">
                    <div class="section-title text-center mb-65">
                        <h2>Features Products</h2>
                        {{-- <p></p> --}}
                    </div>
                </div>
            </div>
            <div class="row pro-active">
                @foreach ($featuredProducts as $variant)
                    <div class="col-xl-4 cl-lg-4 col-md-6">
                        <div class="product-wrapper text-center mb-45">
                            <div class="product-img pos-rel">
                                <a href="product-details.html">
                                    <img src="{{ $variant->image }}" alt="">
                                    <img class="secondary-img" src="{{ $variant->image }}" alt="{{ $variant->name }}">
                                </a>
                                <div class="product-action">
                                    <a class="action-btn" href="#"><i class="far fa-heart"></i></a>
                                    <a class="c-btn" href="#">add to cart <i class="far fa-plus"></i></a>
                                    <a class="action-btn" href="#"><i class="far fa-search"></i></a>
                                </div>
                            </div>
                            <div class="product-text">
                                <h5>{{ $variant->category_name }}</h5>
                                <h4><a href="{{ $variant->link }}">{{ $variant->name }}</a></h4>
                                <span>{!! $variant->price_with_currency !!}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-xl-4">
                    <div class="product-wrapper text-center">
                        <div class="product-img pos-rel">
                            <a href="#">
                                <img src="assets/img/products/02.jpg" alt="">
                                <img class="secondary-img" src="assets/img/products/03.jpg" alt="">
                            </a>
                            <div class="product-action">
                                <a class="action-btn" href="#"><i class="far fa-heart"></i></a>
                                <a class="c-btn" href="#">add to cart <i class="far fa-plus"></i></a>
                                <a class="action-btn" href="#"><i class="far fa-search"></i></a>
                            </div>
                        </div>
                        <div class="product-text">
                            <h5>hand sanitizer</h5>
                            <h4><a href="#">Hand Sanitizer Covid -19</a></h4>
                            <span>$250.99</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="product-wrapper text-center">
                        <div class="product-img pos-rel">
                            <a href="#">
                                <img src="assets/img/products/03.jpg" alt="">
                                <img class="secondary-img" src="assets/img/products/04.jpg" alt="">
                            </a>
                            <div class="product-action">
                                <a class="action-btn" href="#"><i class="far fa-heart"></i></a>
                                <a class="c-btn" href="#">add to cart <i class="far fa-plus"></i></a>
                                <a class="action-btn" href="#"><i class="far fa-search"></i></a>
                            </div>
                        </div>
                        <div class="product-text">
                            <h5>home accessories</h5>
                            <h4><a href="#">Medical Thermometer</a></h4>
                            <span>$250.99</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="product-wrapper text-center">
                        <div class="product-img pos-rel">
                            <a href="#">
                                <img src="assets/img/products/01.jpg" alt="">
                                <img class="secondary-img" src="assets/img/products/02.jpg" alt="">
                            </a>
                            <div class="product-action">
                                <a class="action-btn" href="#"><i class="far fa-heart"></i></a>
                                <a class="c-btn" href="#">add to cart <i class="far fa-plus"></i></a>
                                <a class="action-btn" href="#"><i class="far fa-search"></i></a>
                            </div>
                        </div>
                        <div class="product-text">
                            <h5>accessories</h5>
                            <h4><a href="#">Medical Microscope</a></h4>
                            <span>$250.99</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- product-area-end -->
@endsection
