<header class="main-header" id="header">

  <!-- Top Row -->
  <div class="header-top">
    <div class="container d-flex justify-content-between align-items-center">

      <!-- Logo -->
      <a href="{{ route('home') }}" class="navbar-brand d-flex align-items-center">
        <img src="{{ asset(setting('site_logo', 'theme/xtremez/assets/images/logo.png')) }}"
          alt="{{ setting('site_title', 'Xtremez') }}" class="img-fluid">
      </a>

      <!-- Right: Cart + Sign In (desktop) + Mobile Toggle -->
      <div class="header-actions d-flex align-items-center">
        <!-- Cart -->
        <a href="#" class="cart-link">
          <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
            <g id="SVGRepo_iconCarrier">
              <path d="M17 17L21 21" stroke="#323232" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              </path>
              <path
                d="M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z"
                stroke="#323232" stroke-width="2"></path>
            </g>
          </svg>
          {{-- <span class="cart-text">MY CART</span> --}}
        </a>
        <a href="{{ route('cart.index') }}" class="cart-link">
          <div class="cart-icon-wrapper">
            {{-- <i class="bi bi-cart"></i> --}}

            <svg class="icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#000000"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
              <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
              <g id="SVGRepo_iconCarrier">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <path d="M16 10a4 4 0 0 1-8 0"></path>
              </g>
            </svg>
            <span class="cart-badge" id="cart-items-count"
              style="{{ cart_items_count() > 0 ? '' : 'display:none' }}">{{ cart_items_count() }}</span>
          </div>
          {{-- <span class="cart-text">MY CART</span> --}}
        </a>


        <a href="{{ route('contact-us') }}" class="btn btn-primary d-none d-md-flex">Contact us</a>

        <!-- Hamburger toggle (mobile only) -->
        <button class="btn btn-circle-outline d-lg-none no-animate" id="mobileNavToggle" aria-label="Open navigation">
          <i class="bi bi-list fs-5"></i>
        </button>
      </div>
    </div>
  </div>

  <!-- Bottom Row (hidden on mobile) -->
  <div class="header-bottom d-none d-lg-block">
    <div class="container">

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
                  {{ $menu['label'] }} <i class="bi bi-caret-down-fill"></i>
                </a>

                <ul class="submenu mt-4">

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
      {{-- <a href="{{ route('contact-us') }}" class="btn btn-dark">Contact Us</a> --}}
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
