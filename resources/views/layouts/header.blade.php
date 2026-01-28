<div class="container-fluid px-5 d-none border-bottom d-lg-block">
    <div class="row gx-0 align-items-center">
        {{-- LEFT: Help / Support --}}
        <div class="col-lg-4 text-center text-lg-start mb-lg-0">
            <div class="d-inline-flex align-items-center" style="height: 45px;">
                <a href="{{ route('help.index') }}" class="text-muted me-2"> Help</a><small> / </small>
                <a href="{{ route('help.index') }}" class="text-muted mx-2"> Support</a><small> / </small>
                <a href="{{ route('contact.index') }}" class="text-muted ms-2"> Contact</a>
            </div>
        </div>

        {{-- CENTER: Phone --}}
        <div class="col-lg-4 text-center d-flex align-items-center justify-content-center">
            <small class="text-dark">Call Us:</small>
            <a href="#" class="text-muted">(+012) 1234 567890</a>
        </div>

        {{-- RIGHT: Currency, Language & USER ACCOUNT --}}
        <div class="col-lg-4 text-center text-lg-end">
            <div class="d-inline-flex align-items-center" style="height: 45px;">

                {{-- Currency Dropdown --}}
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle text-muted me-2" data-bs-toggle="dropdown"><small> {{ session('currency', 'USD') }}</small></a>
                    <div class="dropdown-menu rounded">
                        <a href="{{ route('currency.switch', 'USD') }}" class="dropdown-item {{ session('currency') == 'USD' ? 'active' : '' }}"> USD</a>
                        <a href="{{ route('currency.switch', 'EUR') }}" class="dropdown-item {{ session('currency') == 'EUR' ? 'active' : '' }}"> EUR</a>
                    </div>
                </div>

                {{-- Language Dropdown --}}
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle text-muted mx-2" data-bs-toggle="dropdown"><small> {{ session('locale', 'en') == 'en' ? 'English' : 'Vietnamese' }}</small></a>
                    <div class="dropdown-menu rounded">
                        <a href="{{ route('lang.switch', 'en') }}" class="dropdown-item {{ session('locale') == 'en' ? 'active' : '' }}"> English</a>
                        <a href="{{ route('lang.switch', 'vi') }}" class="dropdown-item {{ session('locale') == 'vi' ? 'active' : '' }}"> Vietnamese</a>
                    </div>
                </div>

                {{-- === PHẦN XỬ LÝ TÀI KHOẢN (DESKTOP) === --}}

                @guest
                {{-- 1. TRƯỜNG HỢP KHÁCH (CHƯA LOGIN) --}}
                <div class="d-flex align-items-center ms-3">
                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 me-2" style="font-size: 0.8rem;">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-sm btn-primary rounded-pill px-3 py-1 text-white" style="font-size: 0.8rem;">
                        Register
                    </a>
                </div>
                @endguest

                @auth
                {{-- 2. TRƯỜNG HỢP ĐÃ LOGIN --}}
                <div class="dropdown ms-3">
                    <a href="#" class="dropdown-toggle d-flex align-items-center text-decoration-none" data-bs-toggle="dropdown">
                        {{-- Avatar --}}
                        @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                        @else
                        <div class="rounded-circle bg-primary d-flex justify-content-center align-items-center text-white fw-bold me-2"
                            style="width: 32px; height: 32px; font-size: 14px;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        @endif
                        <small class="text-dark fw-bold">{{ Auth::user()->name }}</small>
                    </a>

                    {{-- Menu Dropdown --}}
                    <div class="dropdown-menu dropdown-menu-end rounded shadow-sm m-0 border-0">
                        @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="dropdown-item text-primary fw-bold">
                            <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
                        </a>
                        <div class="dropdown-divider"></div>
                        @endif

                        <a href="{{ route('profile.edit') }}" class="dropdown-item"><i class="fas fa-user me-2"></i>My Profile</a>

                        {{-- ĐÃ SỬA LỖI Ở DÒNG DƯỚI ĐÂY (Thêm dấu nháy đơn ') --}}
                        <a href="{{ route('orders.index') }}" class="dropdown-item"><i class="fas fa-history me-2"></i>Order History</a>

                        <a href="{{ route('wishlist.index') }}" class="dropdown-item"><i class="fas fa-heart me-2"></i>Wishlist</a>

                        <div class="dropdown-divider"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
                @endauth

            </div>
        </div>
    </div>
</div>

{{-- SEARCH BAR --}}
<div class="container-fluid px-5 py-4 d-none d-lg-block">
    <div class="row gx-0 align-items-center text-center">
        <div class="col-md-4 col-lg-3 text-center text-lg-start">
            <div class="d-inline-flex align-items-center">
                <a href="{{ route('home') }}" class="navbar-brand p-0">
                    <h1 class="display-5 text-primary m-0"><i class="fas fa-shopping-bag text-secondary me-2"></i>Electro</h1>
                </a>
            </div>
        </div>
        <div class="col-md-4 col-lg-6 text-center">
            <div class="position-relative ps-4">
                <form method="GET" action="{{ route('search') }}" class="d-flex border rounded-pill">
                    <input name="q" class="form-control border-0 rounded-pill w-100 py-3" type="text" placeholder="Search Looking For?" value="{{ request('q') }}">
                    <button type="submit" class="btn btn-primary rounded-pill py-3 px-5" style="border: 0;"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>
        <div class="col-md-4 col-lg-3 text-center text-lg-end">
            <div class="d-inline-flex align-items-center">
                <a href="#" class="text-muted d-flex align-items-center justify-content-center me-3"><span class="rounded-circle btn-md-square border"><i class="fas fa-heart"></i></span></a>
                <a href="{{ route('cart.index') }}" class="text-muted d-flex align-items-center justify-content-center"><span class="rounded-circle btn-md-square border"><i class="fas fa-shopping-cart"></i></span> <span class="text-dark ms-2">${{ number_format($cartTotal, 2) }} ({{ $cartCount }})</span></a>
            </div>
        </div>
    </div>
</div>

{{-- NAVBAR (MOBILE MENU) - Đã thêm class d-lg-none để ẩn trên PC --}}
<div class="container-fluid nav-bar p-0 d-lg-none">
    <div class="row gx-0 bg-primary px-5 align-items-center">
        <div class="col-lg-3 d-none d-lg-block">
            <nav class="navbar navbar-light position-relative" style="width: 250px;">
                <button class="navbar-toggler border-0 fs-4 w-100 px-0 text-start" type="button" data-bs-toggle="collapse" data-bs-target="#allCat">
                    <h4 class="m-0"><i class="fa fa-bars me-2"></i>All Categories</h4>
                </button>
                <div class="collapse navbar-collapse rounded-bottom" id="allCat">
                    <div class="navbar-nav ms-auto py-0">
                        <ul class="list-unstyled categories-bars">
                            @foreach($categories as $category)
                            <li>
                                <div class="categories-bars-item">
                                    <a href="{{ route('shop.index', ['category' => $category->slug]) }}">{{ $category->name }}</a>
                                    <span>({{ $category->products->count() }})</span>
                                </div>
                                @if($category->children->count() > 0)
                                <ul class="list-unstyled ms-3">
                                    @foreach($category->children as $child)
                                    <li>
                                        <div class="categories-bars-item">
                                            <a href="{{ route('shop.index', ['category' => $child->slug]) }}">{{ $child->name }}</a>
                                            <span>({{ $child->products->count() }})</span>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <div class="col-12 col-lg-9">
            <nav class="navbar navbar-expand-lg navbar-light bg-primary">
                <a href="{{ route('home') }}" class="navbar-brand d-block d-lg-none">
                    <h1 class="display-5 text-secondary m-0">Electro</h1>
                </a>
                <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars fa-1x"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto py-0">
                        <a href="{{ route('home') }}" class="nav-item nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                        <a href="{{ route('shop.index') }}" class="nav-item nav-link {{ request()->routeIs('shop') ? 'active' : '' }}">Shop</a>
                        <a href="{{ route('contact.index') }}" class="nav-item nav-link">Contact</a>

                        {{-- MENU MOBILE: Login/Register/Logout --}}
                        @guest
                        <a href="{{ route('login') }}" class="nav-item nav-link">Login</a>
                        <a href="{{ route('register') }}" class="nav-item nav-link">Register</a>
                        @else
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">{{ Auth::user()->name }}</a>
                            <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                <a href="{{ route('profile.edit') }}" class="dropdown-item">My Profile</a>
                                <a href="{{ route('orders.index') }}" class="dropdown-item">Order History</a>

                                {{-- Form Logout Mobile --}}
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </div>
                        </div>
                        @endguest
                    </div>
                    <a href="#" class="btn btn-secondary rounded-pill py-2 px-4 px-lg-3 mb-3 mb-md-3 mb-lg-0 d-none d-lg-block"><i class="fa fa-mobile-alt me-2"></i> +0123 456 7890</a>
                </div>
            </nav>
        </div>
    </div>
</div>