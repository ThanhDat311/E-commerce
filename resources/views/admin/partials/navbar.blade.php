<nav class="navbar navbar-expand navbar-light topbar mb-4 static-top">

    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search ms-3">
        <div class="input-group">
            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button" style="background-color: var(--brand-primary); border-color: var(--brand-primary)">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>

    <ul class="navbar-nav ms-auto">

        <div class="topbar-divider d-none d-sm-block" style="width: 0; border-right: 1px solid #e3e6f0; height: calc(4.375rem - 2rem); margin: auto 1rem;"></div>

        <li class="nav-item dropdown no-arrow pe-3">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="me-2 d-none d-lg-inline text-gray-600 small" style="color: var(--sidebar-text); font-weight: 600;">
                    {{ Auth::user()->name ?? 'Admin' }}
                </span>
                <img class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60" style="height: 2rem; width: 2rem;">
            </a>
            <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                    <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>
                    Profile
                </a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>
                        Logout
                    </a>
                </form>
            </div>
        </li>

    </ul>

</nav>