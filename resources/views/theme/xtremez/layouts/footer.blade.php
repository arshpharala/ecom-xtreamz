      <footer class="site-footer pt-5" id="footer">

        <div class="container py-2">
          <div class="row gy-4 justify-content-between align-items-start">

            <!-- Logo + Social -->
            <div class="col-md-4">
              <img src="{{ asset(setting('footer_logo', 'theme/xtremez/assets/images/logo.png')) }}" alt="Logo" class="footer-logo mb-3">
              <div class="social-icons d-flex gap-3">
                @if (setting('facebook'))
                  <a href="{{ setting('facebook') }}" class="text-dark"><i class="bi bi-facebook fs-5"></i></a>
                @endif
                @if (setting('instagram'))
                  <a href="{{ setting('instagram') }}" class="text-dark"><i class="bi bi-instagram fs-5"></i></a>
                @endif
                @if (setting('pinterest'))
                  <a href="{{ setting('pinterest') }}" class="text-dark"><i class="bi bi-pinterest fs-5"></i></a>
                @endif
                @if (setting('twitter'))
                  <a href="{{ setting('twitter') }}" class="text-dark"><i class="bi bi-twitter fs-5"></i></a>
                @endif
              </div>
            </div>

            <!-- Links Group 1 -->
            <div class="col-6 col-md-2">
              <ul class="footer-links list-unstyled">
                <li><a href="{{ route('products') }}">Products</a></li>
                <li><a href="{{ route('clearance') }}">Clearance</a></li>
                <li><a href="{{ route('featured') }}">Featured</a></li>
              </ul>
            </div>

            <!-- Links Group 2 -->
            <div class="col-6 col-md-2">
              <ul class="footer-links list-unstyled">
                <li><a href="{{ route('about-us') }}">About</a></li>
                <li><a href="{{ route('contact-us') }}">Contact</a></li>
                <li><a href="{{ route('policy') }}">Privacy Policy</a></li>
              </ul>
            </div>

            <!-- Account Links -->
            <div class="col-6 col-md-2">
              <ul class="footer-links list-unstyled">
                <li><a href="{{ route('cart.index') }}">Cart</a></li>
                <li><a href="{{ route('customers.profile') }}">My Account</a></li>
                <li><a href="{{ route('login') }}">Create Account</a></li>
              </ul>
            </div>

          </div>
        </div>

        <!-- Bottom Bar -->
        <div class="footer-bottom text-white text-center py-4 mt-4">
          <small>{{ setting('copyright', 'COPYRIGHT 2024 - XTREMEZ | ALL RIGHTS RESERVED') }}</small>
        </div>
        </div>

      </footer>
