<footer class="site-footer pt-5" id="footer">

  <div class="container pb-4">
    <div class="row gy-4">
      <!-- Brand + Social -->
      <div class="col-12 col-lg-3">
        <img src="{{ asset(setting('footer_logo', 'theme/xtremez/assets/images/logo.png')) }}"
             alt="Logo" class="footer-logo mb-3">
        <p class="footer-about text-muted mb-3">
          Premium corporate gifts with fast delivery and trusted support.
        </p>
        <div class="social-icons d-flex gap-3">
          @if (setting('facebook'))
            <a href="{{ setting('facebook') }}" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
          @endif
          @if (setting('instagram'))
            <a href="{{ setting('instagram') }}" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
          @endif
          @if (setting('pinterest'))
            <a href="{{ setting('pinterest') }}" aria-label="Pinterest"><i class="bi bi-pinterest"></i></a>
          @endif
          @if (setting('twitter'))
            <a href="{{ setting('twitter') }}" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
          @endif
        </div>
      </div>

      <!-- Shop -->
      <div class="col-6 col-lg-2">
        <h6 class="footer-title">Shop</h6>
        <ul class="footer-links list-unstyled">
          <li><a href="{{ route('products') }}">All Products</a></li>
          <li><a href="{{ route('featured') }}">Featured</a></li>
          <li><a href="{{ route('clearance') }}">Clearance</a></li>
          <li><a href="{{ route('cart.index') }}">Cart</a></li>
        </ul>
      </div>

      <!-- Company -->
      <div class="col-6 col-lg-2">
        <h6 class="footer-title">Company</h6>
        <ul class="footer-links list-unstyled">
          <li><a href="{{ route('about-us') }}">About</a></li>
          <li><a href="{{ route('contact-us') }}">Contact</a></li>
          <li><a href="{{ '' }}">FAQ</a></li>
          <li><a href="{{ route('customers.profile') }}">My Account</a></li>
        </ul>
      </div>

      <!-- Policies -->
      <div class="col-6 col-lg-2">
        <h6 class="footer-title">Policies</h6>
        <ul class="footer-links list-unstyled">
          <li><a href="{{ route('policy') }}">Privacy Policy</a></li>
          <li><a href="{{ '' }}">Terms & Conditions</a></li>
          <li><a href="{{ '' }}">Shipping Policy</a></li>
          <li><a href="{{ '' }}">Returns & Refunds</a></li>
        </ul>
      </div>

      <!-- Subscribe -->
      <div class="col-12 col-lg-3">
        <h6 class="footer-title">Subscribe</h6>
        <p class="text-muted mb-3">Get exclusive offers, new arrivals & insider perks.</p>
        <form class="subscribe-form" method="POST" action="{{ '' }}" novalidate>
          @csrf
          <div class="input-group input-group-lg subscribe-group fs-6">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" class="form-control fs-6" name="email" id="subscribeEmail"
                   placeholder="Your email address" required>
            <button class="btn btn-subscribe" type="submit">
                <i class="bi bi-arrow-right-short ms-1"></i>
            </button>
          </div>
          <small class="form-hint text-muted d-block mt-2">
            We respect your privacy. Unsubscribe anytime.
          </small>
        </form>
      </div>
    </div>
  </div>

  <!-- Bottom -->
  <div class="footer-bottom py-3">
    <div class="container">
      <div class="row align-items-center gy-3">
        <!-- Left: mini links -->
        <div class="col-12 col-md-4 order-2 order-md-1">
          <ul class="footer-mini-links list-unstyled d-flex flex-wrap gap-3 mb-0 justify-content-center justify-content-md-start">
            <li><a href="{{ '' }}">Sitemap</a></li>
            <li><a href="{{ '' }}">Careers</a></li>
            <li><a href="{{ '' }}">Support</a></li>
          </ul>
        </div>

        <!-- Center: copyright -->
        <div class="col-12 col-md-4 text-center order-1 order-md-2">
          <small class="copy">
            {{ setting('copyright', 'COPYRIGHT 2024 - XTREMEZ | ALL RIGHTS RESERVED') }}
          </small>
        </div>

        <!-- Right: payment/trust -->
        <div class="col-12 col-md-4 order-3 order-md-3">
          <ul class="payment-icons list-unstyled d-flex align-items-center gap-2 mb-0 justify-content-center justify-content-md-end">
            <li class="pay visa" title="Visa" aria-label="Visa">
              <svg viewBox="0 0 64 24" width="64" height="24" aria-hidden="true">
                <rect width="64" height="24" rx="4" fill="#fff"/><text x="50%" y="60%" text-anchor="middle" font-size="12" font-weight="700" fill="#1a1f71">VISA</text>
              </svg>
            </li>
            <li class="pay mc" title="Mastercard" aria-label="Mastercard">
              <svg viewBox="0 0 64 24" width="64" height="24" aria-hidden="true">
                <rect width="64" height="24" rx="4" fill="#fff"/><circle cx="28" cy="12" r="6" fill="#eb001b"/><circle cx="36" cy="12" r="6" fill="#f79e1b" opacity="0.9"/>
              </svg>
            </li>
            <li class="pay amex" title="American Express" aria-label="American Express">
              <svg viewBox="0 0 64 24" width="64" height="24" aria-hidden="true">
                <rect width="64" height="24" rx="4" fill="#fff"/><text x="50%" y="60%" text-anchor="middle" font-size="10" font-weight="700" fill="#0077a6">AMEX</text>
              </svg>
            </li>
            <li class="pay pp" title="PayPal" aria-label="PayPal">
              <svg viewBox="0 0 64 24" width="64" height="24" aria-hidden="true">
                <rect width="64" height="24" rx="4" fill="#fff"/><text x="50%" y="60%" text-anchor="middle" font-size="10" font-weight="700" fill="#003087">PayPal</text>
              </svg>
            </li>
            <li class="pay cod" title="Cash on Delivery" aria-label="Cash on Delivery">
              <i class="bi bi-cash-coin"></i>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

</footer>
