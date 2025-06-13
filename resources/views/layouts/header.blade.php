<style>
    .nav-link:hover {
        text-decoration: none;
        color: #2e9323 !important;
        transform: scale(1.10);
    }
    
    /* Hamburger menu icon sizing - only apply on mobile */
    @media (max-width: 991px) {
        .navbar-toggler {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .navbar-toggler-icon {
            width: 1.25em;
            height: 1.25em;
        }
    }
    
    /* Responsive navbar styles */
    @media (max-width: 991px) {
        .navbar-mobile-menu {
            position: fixed;
            top: 0;
            left: -100%;
            width: 80%;
            max-width: 300px;
            height: 100vh;
            background-color: white;
            z-index: 1050;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            padding: 1.5rem;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            overflow-y: auto;
        }
        
        .navbar-mobile-menu.show {
            left: 0;
        }
        
        .navbar-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1040;
            display: none;
        }
        
        .navbar-backdrop.show {
            display: block;
        }
        
        .mobile-menu-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .mobile-menu-close {
            border: none;
            background: transparent;
            font-size: 1.5rem;
            color: #6c757d;
        }
    }
</style>

<nav class="navbar navbar-expand-lg bg-light bg-gradient shadow">
    <div class="container">
        <a class="navbar-brand text-white d-flex align-items-center" href="/">
            <img src="{{ asset('images/smk.png') }}" alt="Logo Sekolah" height="40" class="me-2">
            <span class="fw-semibold text-black" style="font-size: clamp(0.9rem, 3vw, 1.25rem);">SMK NAGRAK PURWAKARTA</span>
        </a>
        
        <!-- Hamburger menu button for mobile -->
        <button class="navbar-toggler d-lg-none" type="button" id="mobile-menu-toggle">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Desktop menu -->
        <div class="d-none d-lg-flex align-items-center">
            <a class="nav-link text-black px-3" href="/">Home</a>
            {{-- Hidden Rooms link --}}
            <a class="nav-link text-black px-3" href="/assets">Assets</a>

            @guest
                <a class="nav-link text-black px-3" href="/login">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </a>
            @else
                <div class="dropdown">
                    <a class="nav-link text-black px-3 dropdown-toggle" href="#" id="navbarUserDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user me-2"></i>Halo, {{ Auth::user()->nama }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdown">
                        <li><a class="dropdown-item" href="/profile">Profile</a></li>

                        @if (Auth::user()->role === 'super_admin' || Auth::user()->role === 'resepsionis')
                            <li><a class="dropdown-item" href="/dashboard">Dashboard</a></li>
                        @elseif (Auth::user()->role === 'user')
                            <li><a class="dropdown-item" href="{{ route('assets.borrow.index') }}">List Peminjaman</a></li>
                        @endif

                        {{-- Hidden Customer Service link --}}
                        {{-- <li><a class="dropdown-item" href="/customerservice">Customer Service</a></li> --}}
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endguest
        </div>
    </div>
</nav>

<!-- Mobile Menu - completely hidden on desktop -->
<div class="d-lg-none">
    <div class="navbar-backdrop" id="navbar-backdrop"></div>
    <div class="navbar-mobile-menu" id="navbar-mobile-menu">
    <div class="mobile-menu-header">
        <h5 class="m-0">Menu</h5>
        <button class="mobile-menu-close" id="mobile-menu-close">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="mobile-menu-items">
        <a class="nav-link text-black py-2" href="/">Home</a>
        {{-- Hidden Rooms link --}}
        <a class="nav-link text-black py-2" href="/assets">Assets</a>
        
        @guest
            <a class="nav-link text-black py-2" href="/login">
                <i class="fas fa-sign-in-alt me-2"></i>Login
            </a>
        @else
            <div class="mt-3 border-top pt-3">
                <p class="mb-2 text-muted">Halo, {{ Auth::user()->nama }}</p>
                <a class="nav-link text-black py-2" href="/profile">Profile</a>
                
                @if (Auth::user()->role === 'super_admin' || Auth::user()->role === 'resepsionis')
                    <a class="nav-link text-black py-2" href="/dashboard">Dashboard</a>
                @elseif (Auth::user()->role === 'user')
                    <a class="nav-link text-black py-2" href="{{ route('assets.borrow.index') }}">List Peminjaman</a>
                @endif
                
                {{-- Hidden Customer Service link --}}
                {{-- <a class="nav-link text-black py-2" href="/customerservice">Customer Service</a> --}}
                
                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm w-100">Logout</button>
                </form>
            </div>
        @endguest
    </div>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('navbar-mobile-menu');
        const mobileMenuClose = document.getElementById('mobile-menu-close');
        const backdrop = document.getElementById('navbar-backdrop');
        
        function openMobileMenu() {
            mobileMenu.classList.add('show');
            backdrop.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        
        function closeMobileMenu() {
            mobileMenu.classList.remove('show');
            backdrop.classList.remove('show');
            document.body.style.overflow = '';
        }
        
        mobileMenuToggle.addEventListener('click', openMobileMenu);
        mobileMenuClose.addEventListener('click', closeMobileMenu);
        backdrop.addEventListener('click', closeMobileMenu);
    });
</script>
