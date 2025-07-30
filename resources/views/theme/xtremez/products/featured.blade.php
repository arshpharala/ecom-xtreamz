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
          <li class="breadcrumb-item active text-white" aria-current="page" title="{{ $page->title ?? 'Featured' }}">
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
        <h2 class="section-title fs-1 text-center m-0">Featured</h2>
      </div>
    </div>
  </section>


  <section class="gift-set-section py-5">
    <div class="container">
      <div class="row g-0 gift-set-grid">
        <!-- LEFT: Full-height box -->
        <div class="col-lg-6 animate-on-scroll" data-animate="fade-right">
          <div class="gift-box h-100 gift-left position-relative  p-4">
            <div class="z-2 px-3">
              <h2 class="mb-3">Gift Set</h2>

            </div>
            <div class="z-2 d-flex justify-content-between px-3">
              <p class="fs-5 fw-semibold mt-3">
                SKROSS - Gift Set of Powerbank,
                Travel
                Adapter & Charging
                Cable
              </p>
              <p class="fs-2 fw-normal mt-3 z-2">89
                AED</p>

            </div>
            <img src="assets/images/gift-set-left.png" alt="Gift Set" class="gift-img img-left-big">
          </div>
        </div>

        <!-- RIGHT: Two stacked half-height boxes -->
        <div class="col-lg-6 d-flex flex-column animate-on-scroll" data-animate="fade-left">
          <!-- Top box -->
          <div
            class="gift-box gift-right-half bg-lightblue flex-fill position-relative text-white p-4 d-flex flex-column justify-content-between">
            <div class="z-2 px-4">
              <p class="fs-5 fw-bold my-3">BEBRA - XD
                Bamboo
                Free Flow TWS
                Earbuds in Charging Case - Black</p>
              <p class="fs-2 fw-normal">89 AED</p>
            </div>
            <img src="assets/images/gift-set-top.png" alt="TWS" class="gift-img img-right-small">
          </div>

          <!-- Bottom box -->
          <div
            class="gift-box gift-right-half bg-tan flex-fill position-relative text-white p-4 d-flex flex-column justify-content-between">
            <img src="assets/images/gift-set-bottom.png" alt="Gift Box" class="gift-img img-left-small">
            <div class="z-2 text-start px-4">
              <p class="fs-5 fw-bold my-3">BEBRA - XD
                Bamboo
                Free Flow TWS
                Earbuds in Charging Case - Black</p>
              <p class="fs-2 fw-normal">89 AED</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
