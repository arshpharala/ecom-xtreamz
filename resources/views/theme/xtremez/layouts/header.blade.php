      <header class="main-header border-bottom" id="header">


        <div class="container d-flex justify-content-between align-items-center">
          <!-- Logo -->
          <a href="{{ route('home') }}" class="navbar-brand d-flex align-items-center">
            <img src="{{ asset(setting('site_logo', 'theme/xtremez/assets/images/logo.png')) }}" alt="{{ setting('site_title', 'Xtremez') }}" class="img-fluid">
          </a>

          <!-- Main Navigation (ALWAYS in DOM, hidden on mobile by CSS) -->
          <nav class="main-nav">
            <ul class="nav">
              <li class="nav-item"><a href="{{ route('home') }}" class="nav-link">Home</a></li>
              <li class="nav-item"><a href="{{ route('about-us') }}" class="nav-link">About</a></li>
              <li class="nav-item"><a href="{{ route('products') }}" class="nav-link">Products</a></li>
              <li class="nav-item"><a href="{{ route('contact-us') }}" class="nav-link">Contact</a></li>
            </ul>
          </nav>

          <!-- Icons & Mobile Toggle -->
          <div class="header-icons d-flex align-self-baseline gap-3">
            @auth
              <div class="dropdown">
                <button class="border-0 bg-transparent d-flex align-items-center gap-2" id="userMenuButton"
                  data-bs-toggle="dropdown" aria-expanded="false" aria-label="User menu">
                  <i class="bi bi-person fs-5"></i>
                  <i class="bi bi-chevron-down fs-6"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userMenuButton">
                  <li>
                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('customers.profile') }}">
                      <i class="bi bi-person-circle"></i> Profile
                    </a>
                  </li>
                  <li>
                    <form method="POST" action="{{ route('logout') }}">
                      @csrf
                      <button type="submit" class="dropdown-item d-flex align-items-center gap-2">
                        <i class="bi bi-box-arrow-right"></i> Logout
                      </button>
                    </form>
                  </li>
                </ul>
              </div>
            @else
              <a href="{{ route('login') }}" class="icon-link"><i class="bi bi-person fs-5"></i></a>
            @endauth
            <a href="{{ route('cart.index') }}" class="icon-link position-relative align-items-baseline">
              <i class="bi bi-cart fs-5"></i>
              <span class="badge bg-primary position-absolute top-0 start-100 translate-middle badge-sm"
                id="cart-items-count">{{ cart_items_count() }}</span>
            </a>
            <!-- Hamburger button, mobile only -->
            <button class="btn p-0 border-0 d-lg-none no-animate" id="mobileNavToggle" aria-label="Open navigation">
              <i class="bi bi-list fs-4"></i>
            </button>
          </div>
        </div>

        <!-- Slide Menu and Mask (mobile only, always in DOM) -->
        <div class="mobile-nav-drawer">
          <nav class="mobile-nav">
            <ul class="nav">
              <li class="nav-item"><a href="{{ route('home') }}" class="nav-link">Home</a></li>
              <li class="nav-item"><a href="{{ route('about-us') }}" class="nav-link">About</a></li>
              <li class="nav-item"><a href="{{ route('products') }}" class="nav-link">Products</a></li>
              <li class="nav-item"><a href="{{ route('contact-us') }}" class="nav-link">Contact</a></li>
            </ul>
          </nav>
          <div class="nav-mask"></div>
        </div>

      </header>
