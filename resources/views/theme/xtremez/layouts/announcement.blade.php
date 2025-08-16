    <section class="announcement">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center">

          <!-- Swiper -->
          <div class="swiper announcement-swiper flex-grow-1">
            <div class="swiper-wrapper">
              @foreach (header_offers() as $offer)
                <div class="swiper-slide">
                  <i class="bi-ticket-detailed"></i>
                  {!! $offer->translation->description ?? '' !!}
                </div>
              @endforeach
            </div>
          </div>

          <!-- User Menu -->

          <a href="{{ route('login') }}" class="nav-link ms-3 text-nowrap d-none d-md-flex align-items-center"><i
              class="bi bi-person"></i>My Account</a>

        </div>
      </div>
    </section>
