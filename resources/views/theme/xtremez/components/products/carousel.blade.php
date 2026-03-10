@php
  if (!$productUrl) {
      $productUrl = route('ajax.get-products', ['category_id' => $category->id]);
  }

  $carouselType = $type ?? $id;
  $carouselLimit = $limit ?? 8;
  $carouselItems = $items ?? 4;
@endphp
<section class="heading-section pt-5">
  <div class="container">
    <div class="heading-row align-items-center d-flex justify-content-between">
      <div class="animate-on-scroll" data-animate="fade-right">
        <h2 class="section-title fs-1 m-0">{{ $sectionName }}</h2>
        {{-- <div class="heading-wrapper">
          <div class="heading-accent"></div>
        </div> --}}
      </div>
      <div class="section-nav  d-flex animate-on-scroll" data-animate="fade-left">
        <div id="product{{ $id }}Prev" class="btn-circle-outline" style="margin:0 0 0 0 ">
          <i class="bi bi-arrow-left fw-bold"></i>
        </div>
        <div id="product{{ $id }}Next" class="btn-circle-outline" style="margin: 0 0 0 10px ">
          <i class="bi bi-arrow-right fw-bold"></i>
        </div>
      </div>
    </div>
  </div>
</section>



<section class="product-section animate-on-scroll ajax-carousel" id="carousel-{{ $id }}"
  data-animate="fade-up" data-type="{{ $carouselType }}" data-limit="{{ $carouselLimit }}" data-items="{{ $carouselItems }}"
  @if (!empty($source)) data-source="{{ $source }}" @endif
  data-prev="#product{{ $id }}Prev" data-next="#product{{ $id }}Next"
  data-url="{{ $productUrl }}">
  <div class="container">
    <div class="row g-4 owl-carousel">
    </div>
  </div>
</section>
