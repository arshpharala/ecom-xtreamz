  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link text-center">
      <span class="brand-text font-weight-light text-center">Xtremez</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('theme/adminlte/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
            alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ auth('admin')->user()->name }}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      {{-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> --}}

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p>
                User Management
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.auth.admins.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Users</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.auth.roles.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Roles</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.auth.permissions.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Permissions</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.auth.modules.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Modules</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-search-dollar"></i>
              <p>
                Sales
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.sales.orders.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Orders</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.sales.customers.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Customers</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.sales.subscribers.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Subscribers</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-shopping-bag"></i>
              <p>
                Catalog
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.catalog.products.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Products</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.catalog.categories.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Categories</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.catalog.attributes.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Attributes</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.catalog.brands.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Brands</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.catalog.offers.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Offers</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.catalog.coupons.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Coupons</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.catalog.vendors.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Vendors</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-search-dollar"></i>
              <p>
                Inventory
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.inventory.sources.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Source</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-poll"></i>
              <p>
                CMS
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('admin.cms.pages.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pages</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.cms.banners.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Banners</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.cms.news.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>News</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.cms.testimonials.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Testimonials</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.cms.locales.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Locale</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.cms.currencies.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Currencies</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.cms.countries.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Countries</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.cms.tags.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Tags</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.cms.emails.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Email Management</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.cms.settings.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Configuration</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
