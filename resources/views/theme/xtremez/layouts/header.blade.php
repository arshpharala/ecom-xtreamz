      <header class="main-header border-bottom" id="header">


        <div class="container d-flex justify-content-between align-items-center">
          <!-- Logo -->
          <a href="{{ route('home') }}" class="navbar-brand d-flex align-items-center">
            <img src="{{ asset('theme/xtremez/assets/images/logo.png') }}" alt="Logo" class="img-fluid">
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
          <div class="header-icons d-flex align-items-center gap-3">
            <a href="{{ route('login') }}" class="icon-link"><i class="bi bi-person fs-5"></i></a>
            <a href="{{ route('cart.index') }}" class="icon-link position-relative">
              <i class="bi bi-cart fs-5"></i>
              <span class="badge bg-primary position-absolute top-0 start-100 translate-middle badge-sm">2</span>
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
