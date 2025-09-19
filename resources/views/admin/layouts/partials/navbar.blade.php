<header class="app-header top-0">
    <nav class="navbar navbar-expand-lg navbar-light">
        <ul class="navbar-nav">
            {{-- Sidebar toggle (mobile) --}}
            <li class="nav-item d-block d-xl-none">
                <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                    <i class="ti ti-menu-2"></i>
                </a>
            </li>
        </ul>
        <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                {{-- Tulisan Hai Rihan di kanan --}}
                <li class="nav-item d-none d-sm-block me-3">
                    <span class="nav-link">Hai Rihan</span>
                </li>
                {{-- Profile dropdown --}}
                <li class="nav-item dropdown">
                    <a class="nav-link" href="javascript:void(0)" id="profileDropdown" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <img src="./admin/images/profile/user-1.jpg" alt="profile" width="35" height="35"
                            class="rounded-circle">
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up"
                        aria-labelledby="profileDropdown">
                        <div class="message-body">
                            <a href="{{ url('/profile') }}" class="d-flex align-items-center gap-2 dropdown-item">
                                <i class="ti ti-user fs-6"></i>
                                <p class="mb-0 fs-3">My Profile</p>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <a href="{{ url('/logout') }}" class="btn btn-outline-primary mx-3 mt-2 d-block"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>
