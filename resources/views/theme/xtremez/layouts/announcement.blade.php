    <section class="announcement border-bottom py-1 bg-black text-white">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center">

          <!-- Swiper -->
          <div class="swiper announcement-swiper flex-grow-1">
            <div class="swiper-wrapper">
              @foreach (header_offers() as $offer)
                <div class="swiper-slide">
                  <i class="bi-ticket-detailed"></i> &nbsp;     
                  {!! $offer->translation->description ?? '' !!}
                </div>
              @endforeach
            </div>
          </div>

          <!-- User Menu -->

          <a href="{{ route('login') }}" class="nav-link ms-3 text-nowrap d-none d-md-flex align-items-center"><i class="bi bi-person fs-5"></i>&nbsp; My Account</a>

        </div>
      </div>
    </section>
