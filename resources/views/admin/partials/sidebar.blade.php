<ul class="navbar-nav bg-white sidebar accordion" id="sidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Admin Panel</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading px-3 mt-3 mb-1 text-muted text-xs font-weight-bold text-uppercase">
        E-commerce
    </div>

    <li class="nav-item {{ Route::is('admin.categories.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.categories.index') }}">
            <i class="fas fa-fw fa-tags"></i>
            <span>Categories</span>
        </a>
    </li>

    <li class="nav-item {{ Route::is('admin.products.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.products.index') }}">
            <i class="fas fa-fw fa-box"></i>
            <span>Products</span>
        </a>
    </li>

    <li class="nav-item {{ Route::is('admin.orders.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.orders.index') }}">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Orders</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle" style="width: 40px; height: 40px; background: #eaecf4;"><i class="fa fa-chevron-left"></i></button>
    </div>

</ul>