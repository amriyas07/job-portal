<nav class="top-navbar">
    <button class="mobile-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <a href="{{ route('dashboard') }}" class="navbar-brand">
        <i class="fas fa-briefcase"></i>
        Job Portal
    </a>

    <div class="navbar-search d-none">
        <input type="text" class="form-control" placeholder="Search jobs, candidates...">
    </div>

    <div class="navbar-right">
        <div class="dropdown">
            @php
                $name = Auth::user()->name ?? 'AM';
                $initials = strtoupper(substr($name, 0, 2));
            @endphp
            <div class="user-profile dropdown-toggle" data-bs-toggle="dropdown">
                <div class="user-avatar">{{ $initials }}</div>
                <span>{{ Auth::user()->name ?? 'User' }}</span>
            </div>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                            class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
            </ul>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</nav>
