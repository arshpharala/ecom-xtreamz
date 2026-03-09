    <section class="announcement">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center">

          <!-- Swiper -->
          <div class="swiper announcement-swiper flex-grow-1">
            <div class="swiper-wrapper">
              {{-- @foreach (header_offers() as $offer)
                <div class="swiper-slide">
                  <i class="bi-ticket-detailed"></i>
                  {!! $offer->translation->description ?? '' !!}
                  <a class="ms-2" href="{{ $offer->url ?? route('products', ['offer' => $offer->id]) }}"><u>View Offer</u></a>
                </div>
                @endforeach --}}
              <div class="swiper-slide">
                {{-- <i class="bi-ticket-detailed"></i>
                  {!! $offer->translation->description ?? '' !!} --}}
                <span class="ms-2">Ready Stocks in UAE, Saudi Arabia, Qatar, South Africa and India | Aspirational
                  Brands & Genuine Sustainable Products </span>
              </div>

            </div>
          </div>

          <!-- User Menu -->

          <a href="{{ route('customers.profile') }}#wishlist"
            class="nav-link ms-3 text-nowrap d-none d-md-flex align-items-center">
            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd"
                d="M12 6.00019C10.2006 3.90317 7.19377 3.2551 4.93923 5.17534C2.68468 7.09558 2.36727 10.3061 4.13778 12.5772C5.60984 14.4654 10.0648 18.4479 11.5249 19.7369C11.6882 19.8811 11.7699 19.9532 11.8652 19.9815C11.9483 20.0062 12.0393 20.0062 12.1225 19.9815C12.2178 19.9532 12.2994 19.8811 12.4628 19.7369C13.9229 18.4479 18.3778 14.4654 19.8499 12.5772C21.6204 10.3061 21.3417 7.07538 19.0484 5.17534C16.7551 3.2753 13.7994 3.90317 12 6.00019Z"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
            Wishlist
          </a>
          {{-- <div class="divider text-dark ms-3">|</div> --}}

          <a href="{{ route('customers.profile') }}"
            class="nav-link ms-3 text-nowrap d-none d-md-flex align-items-center">
            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd"
                d="M8 9C8 6.79086 9.79086 5 12 5C14.2091 5 16 6.79086 16 9C16 11.2091 14.2091 13 12 13C9.79086 13 8 11.2091 8 9ZM15.8243 13.6235C17.1533 12.523 18 10.8604 18 9C18 5.68629 15.3137 3 12 3C8.68629 3 6 5.68629 6 9C6 10.8604 6.84668 12.523 8.17572 13.6235C4.98421 14.7459 3 17.2474 3 20C3 20.5523 3.44772 21 4 21C4.55228 21 5 20.5523 5 20C5 17.7306 7.3553 15 12 15C16.6447 15 19 17.7306 19 20C19 20.5523 19.4477 21 20 21C20.5523 21 21 20.5523 21 20C21 17.2474 19.0158 14.7459 15.8243 13.6235Z"
                fill="currentColor"></path>
            </svg>
            My Account
          </a>

        </div>
      </div>
    </section>
