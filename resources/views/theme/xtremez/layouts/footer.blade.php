  <section class="features-strip-section">
    <div class="container">
      <div class="features-strip">
        <div class="row gap-3 align-items-center text-start">

          <div class="col-12 col-sm-6 col-md-5 col-lg d-flex">
            <div class="item w-100">
              <div class="icon-wrap"><i class="bi bi-truck"></i></div>
              <div>
                <div class="title">Easy Free Delivery</div>
                <p class="sub">Orders Above 100 AED</p>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-md-5 col-lg d-flex">
            <div class="item w-100">
              <div class="icon-wrap"><i class="bi bi-shield-check"></i></div>
              <div>
                <div class="title">Secure Payments</div>
                <p class="sub">Trusted payment options.</p>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-md-5 col-lg d-flex">
            <div class="item w-100">
              <div class="icon-wrap"><i class="bi bi-recycle"></i></div>
              <div>
                <div class="title">Easy Returns</div>
                <p class="sub">Fast and easy returns</p>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-md-5 col-lg d-flex">
            <div class="item w-100">
              <div class="icon-wrap"><i class="bi bi-headset"></i></div>
              <div>
                <div class="title">Customer Support</div>
                <p class="sub">Expert Assistance</p>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>
  <footer class="site-footer" id="footer">

    <!-- Main Footer -->
    <div class="footer-top py-5">
      <div class="container">
        <div class="row gy-4">
          <!-- Brand -->
          <div class="col-12 col-lg-3">
            <img src="{{ asset(setting('site_footer_logo', 'theme/xtremez/assets/images/logo.png')) }}" alt="Logo"
              class="footer-logo mb-3">
            <p class="footer-about mb-3">
              Premium corporate gifts with fast delivery and trusted support.
            </p>
            <div class="social-icons d-flex gap-3">
              @if (setting('facebook'))
                <a href="{{ setting('facebook') }}"><i class="bi bi-facebook"></i></a>
              @endif
              @if (setting('instagram'))
                <a href="{{ setting('instagram') }}"><i class="bi bi-instagram"></i></a>
              @endif
              @if (setting('pinterest'))
                <a href="{{ setting('pinterest') }}"><i class="bi bi-pinterest"></i></a>
              @endif
              @if (setting('twitter'))
                {{-- <a href="{{ setting('twitter') }}"><i class="bi bi-twitter-x"></i></a> --}}
                <a href="{{ setting('twitter') }}">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                    class="bi bi-twitter-x" viewBox="0 0 16 16">
                    <path
                      d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z" />
                  </svg>
                </a>
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
              <li><a href="{{ route('faq') }}">FAQ</a></li>
              <li><a href="{{ route('customers.profile') }}">My Account</a></li>
            </ul>
          </div>

          <!-- Policies -->
          <div class="col-6 col-lg-2">
            <h6 class="footer-title">Policies</h6>
            <ul class="footer-links list-unstyled">
              <li><a href="{{ route('policy') }}">Privacy Policy</a></li>
              <li><a href="{{ route('terms-and-conditions') }}">Terms & Conditions</a></li>
              <li><a href="{{ route('shipping-policy') }}">Shipping Policy</a></li>
              <li><a href="{{ route('return-and-refunds') }}">Returns & Refunds</a></li>
            </ul>
          </div>

          <!-- Contact -->
          <div class="col-12 col-lg-3">
            <h6 class="footer-title">Contact Us</h6>
            <div class="footer-links">

              <p class="mb-1">Do you have any questions and suggestions?</p>
              <a href="mailto:{{ setting('contact_email', 'info@xtremez.com') }}"
                class="footer-title text-white text-lowercase">
                <i class="bi bi-envelope"></i>
                {{ setting('contact_email', 'info@xtremez.com') }}
              </a>
              <p class="mb-1 mt-3">Do you need support? Give us a call.</p>
              <a href="tel:{{ setting('contact_phone', '+971522621345') }}" class="footer-title text-white">
                <i class="bi bi-phone"></i>
                {{ setting('contact_phone', '+971522621345') }}
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom -->
    <div class="footer-bottom py-3">
      <div class="container">
        <div class="row align-items-center gy-3">
          <div class="col-12 col-md-4 text-center text-md-start order-2 order-md-1">
            <ul
              class="footer-mini-links list-unstyled d-flex flex-wrap gap-3 mb-0 justify-content-center justify-content-md-start">
              <li><a href="#">Sitemap</a></li>
              <li><a href="#">Careers</a></li>
              {{-- <li><a href="#">Support</a></li> --}}
            </ul>
          </div>
          <div class="col-12 col-md-4 text-center order-1 order-md-2">
            <small class="copy">COPYRIGHT 2025 - XTREMEZ | ALL RIGHTS RESERVED</small>
          </div>
          <div class="col-12 col-md-4 text-center text-md-end order-3">
            <ul class="payment-icons list-unstyled d-flex gap-2 mb-0 justify-content-center justify-content-md-end">
              <li class="pay visa"><img src="https://img.icons8.com/color/48/visa.png" alt="Visa"></li>
              <li class="pay mc"><img src="https://img.icons8.com/color/48/mastercard-logo.png" alt="MasterCard"></li>
              <li class="pay amex"><img src="https://img.icons8.com/color/48/amex.png" alt="American Express"></li>
              <li class="pay pp"><img src="https://img.icons8.com/color/48/paypal.png" alt="PayPal"></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </footer>
