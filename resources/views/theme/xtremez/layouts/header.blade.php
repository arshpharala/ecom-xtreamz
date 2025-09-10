<header class="main-header border-bottom" id="header">

  <!-- Top Row -->
  <div class="header-top py-3">
    <div class="container d-flex justify-content-between align-items-center">

      <!-- Logo -->
      <a href="{{ route('home') }}" class="navbar-brand d-flex align-items-center">
        <img src="{{ asset(setting('site_logo', 'theme/xtremez/assets/images/logo.png')) }}"
          alt="{{ setting('site_title', 'Xtremez') }}" class="img-fluid">
      </a>

      <!-- Right: Cart + Sign In (desktop) + Mobile Toggle -->
      <div class="header-actions d-flex align-items-center gap-3">
        <!-- Cart -->
        <a href="{{ route('cart.index') }}" class="cart-link">
          <div class="cart-icon-wrapper">
            <i class="bi bi-cart"></i>
            <span class="cart-badge" id="cart-items-count"
              style="{{ cart_items_count() > 0 ? '' : 'display:none' }}">{{ cart_items_count() }}</span>
          </div>
          <span class="cart-text">MY CART</span>
        </a>


        <!-- Sign In (desktop only) -->
        @guest
          <a href="{{ route('login') }}" class="btn btn-outline-dark">SIGN IN</a>
        @endguest

        <!-- Hamburger toggle (mobile only) -->
        <button class="btn btn-circle-outline d-lg-none no-animate" id="mobileNavToggle" aria-label="Open navigation">
          <i class="bi bi-list fs-5"></i>
        </button>
      </div>
    </div>
  </div>

  <!-- Bottom Row (hidden on mobile) -->
  <div class="header-bottom border-top d-none d-lg-block">
    <div class="container d-flex justify-content-between align-items-center">

      <!-- Navigation -->
      <nav class="main-nav">
        <ul class="nav align-items-center">
          @foreach (menu_categories(10) as $category)
            @if ($category->children->count() > 0)
              <li class="nav-item dropdown">
                <a href="{{ route('products', ['category' => $category->slug]) }}" class="nav-link has-submenu">
                  {{ $category->name }} <i class="bi bi-chevron-down ms-1"></i>
                </a>
                <ul class="submenu mt-5">
                  @foreach ($category->children as $child)
                    <li>
                      <a href="{{ route('products', ['category' => $child->slug]) }}">
                        <i class="bi bi-circle"></i> {{ $child->translation->name }}
                      </a>
                    </li>
                  @endforeach
                </ul>
              </li>
            @else
              <li class="nav-item">
                <a href="{{ route('products', ['category' => $category->slug]) }}" class="nav-link">
                  {{ $category->name }}
                </a>
              </li>
            @endif
          @endforeach
        </ul>
      </nav>


      <!-- Contact Us -->
      <a href="{{ route('contact-us') }}" class="btn btn-dark">Contact Us</a>
    </div>
  </div>

  <!-- Mobile Drawer -->
  <div class="mobile-nav-drawer">
    <nav class="mobile-nav">
      <ul class="nav flex-column">
        <li class="nav-item"><a href="#" class="nav-link">Sustainable</a></li>
        <li class="nav-item"><a href="#" class="nav-link">Apparel</a></li>
        <li class="nav-item"><a href="#" class="nav-link">Tech</a></li>
        <li class="nav-item"><a href="#" class="nav-link">Drinkware</a></li>
        <li class="nav-item"><a href="#" class="nav-link">Bags</a></li>
        <li class="nav-item"><a href="#" class="nav-link">Office</a></li>
        <li class="nav-item"><a href="#" class="nav-link">Other Brands</a></li>

        <!-- Sign In inside mobile drawer -->
        @guest
          <li class="nav-item mt-3">
            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-rounded w-100">Sign In</a>
          </li>
        @endguest

        <!-- Contact Us inside mobile drawer -->
        <li class="nav-item mt-2">
          <a href="{{ route('contact-us') }}" class="btn btn-primary btn-rounded w-100">Contact Us</a>
        </li>
      </ul>
    </nav>
    <div class="nav-mask"></div>
  </div>
</header>
