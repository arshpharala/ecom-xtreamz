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
          <a href="{{ route('login') }}" class="btn btn-outline-dark d-none d-md-flex">SIGN IN</a>
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
      @if (false)
        <!-- Navigation -->
        <nav class="main-nav">
          <ul class="nav align-items-center">

            @foreach (menu_categories(10) as $category)
              @if ($category->children->count() > 0)
                <li class="nav-item dropdown">
                  <a href="{{ route('products', ['category' => $category->slug]) }}" class="nav-link has-submenu">
                    {{ $category->name }} <i class="bi bi-chevron-down ms-1"></i>
                  </a>
                  <ul class="submenu mt-2">
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
      @endif

      <nav class="main-nav">
        <ul class="nav align-items-center">

          @foreach (header_menu() as $menu)

            {{-- NORMAL LINK --}}
            @if (!$menu['dropdown'])
              <li class="nav-item {{ $menu['class'] ?? '' }}">
                <a href="{{ url($menu['url']) }}" class="nav-link">
                  {{ $menu['label'] }}
                </a>
              </li>
            @endif

            {{-- DROPDOWN --}}
            @if ($menu['dropdown'])
              <li class="nav-item dropdown">
                <a href="{{ url($menu['url']) }}" class="nav-link has-submenu">
                  {{ $menu['label'] }} <i class="bi bi-chevron-down ms-1"></i>
                </a>

                <ul class="submenu mt-2">

                  {{-- STATIC LINKS --}}
                  @if ($menu['type'] === 'static')
                    @foreach ($menu['links'] as $link)
                      <li>
                        <a href="{{ url($link['url']) }}">
                          {{ $link['label'] }}
                        </a>
                      </li>
                    @endforeach
                  @endif

                  {{-- CATEGORY LINKS --}}
                  @if ($menu['type'] === 'category')
                    @foreach ($menu['categories'] as $category)
                      <li class="{{ $category->children->count() ? 'has-children' : '' }}">
                        <a href="{{ route('products', ['category' => $category->slug]) }}">
                          {{ $category->translation->name }}
                        </a>

                        @if ($category->children->count())
                          <ul class="submenu">
                            @foreach ($category->children as $child)
                              <li>
                                <a href="{{ route('products', ['category' => $child->slug]) }}">
                                  <i class="bi bi-circle"></i>
                                  {{ $child->translation->name }}
                                </a>
                              </li>
                            @endforeach
                          </ul>
                        @endif
                      </li>
                    @endforeach
                  @endif

                </ul>
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
  <div class="mobile-nav-drawer" id="mobileNavDrawer">
    <nav class="mobile-nav d-flex flex-column h-100">
      <!-- Drawer Header with Close -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('home') }}" class="navbar-brand">
          <img src="{{ asset(setting('site_logo', 'theme/xtremez/assets/images/logo.png')) }}"
            alt="{{ setting('site_title', 'Xtremez') }}" style="max-height:32px;">
        </a>
        <button class="btn-circle-outline" id="mobileNavClose" aria-label="Close navigation">
          <i class="bi bi-x-lg fs-5"></i>
        </button>
      </div>

      <!-- Drawer Body (scrollable) -->
      <div class="flex-grow-1 overflow-auto">
        <ul class="nav flex-column">
          <li class="nav-item border-bottom">
            <a href="{{ route('home') }}" class="nav-link">Home</a>
          </li>

          @foreach (menu_categories(10) as $category)
            @if ($category->children->count() > 0)
              <li class="nav-item border-bottom">
                <a href="{{ route('products', ['category' => $category->slug]) }}"
                  class="nav-link d-flex justify-content-between align-items-center">
                  {{ $category->name }} <i class="bi bi-chevron-right"></i>
                </a>
              </li>
            @else
              <li class="nav-item border-bottom">
                <a href="{{ route('products', ['category' => $category->slug]) }}"
                  class="nav-link">{{ $category->name }}</a>
              </li>
            @endif
          @endforeach
        </ul>
      </div>

      <!-- Drawer Footer -->
      <div class="mt-4">
        @guest
          <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 mb-2">Sign In</a>
        @endguest
        <a href="{{ route('contact-us') }}" class="btn btn-primary w-100">Contact Us</a>
      </div>
    </nav>
    <div class="nav-mask" id="mobileNavMask"></div>
  </div>

</header>
