<nav class="navbar mytee-navbar is-spaced" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-item logo-text" href="{{ route('home') }}">
            <span class="icon"><i class="fas fa-tshirt"></i></span>
            <span style="margin-left: 6px">MyTee Studio</span>
        </a>

        <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="mainNavbar">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>

    <div id="mainNavbar" class="navbar-menu">
        <div class="navbar-start">
            <a class="navbar-item has-text-weight-semibold" href="{{ route('home') }}">
                <span class="icon"><i class="fas fa-home"></i></span>
                <span>Trang chủ</span>
            </a>

            <a class="navbar-item has-text-weight-semibold" href="{{ route('design') }}">
                <span class="icon"><i class="fas fa-paint-brush"></i></span>
                <span>Thiết kế ngay</span>
            </a>

            @if (Auth::check() && Auth::user()->role == 'admin')
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link"
                        style="background: linear-gradient(90deg, #ff6b6b, #ff8e53); color: #fff; border-radius: 6px;">
                        Quản trị
                    </a>
                    <div class="navbar-dropdown"
                        style="background: rgba(255, 111, 107, 0.95); border-radius: 8px; box-shadow: 0 6px 18px rgba(0,0,0,0.12);">
                        <a class="navbar-item {{ request()->is('admin/products*') ? 'is-active' : '' }}"
                            href="{{ route('admin.products.index') }}"
                             style="color: #000000; transition: background 0.3s;">
                            <span class="icon"><i class="fas fa-tshirt"></i></span>
                            <span>Sản phẩm</span>
                        </a>
                        <a class="navbar-item {{ request()->is('admin/orders*') ? 'is-active' : '' }}"
                            href="{{ route('admin.orders.index') }}"
                             style="color: #000000; transition: background 0.3s;">
                            <span class="icon"><i class="fas fa-shopping-cart"></i></span>
                            <span>Đơn hàng</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>


        <div class="navbar-end">
            <div class="navbar-end">
                @auth
                    <div class="navbar-item has-dropdown is-hoverable">
                        <a class="navbar-link"
                            style="background: linear-gradient(90deg, #ff6b6b, #ff8e53); color: #fff; border-radius: 6px;">
                            Xin chào, {{ Auth::user()->name }}
                        </a>
                        <div class="navbar-dropdown"
                            style="background: rgba(255, 111, 107, 0.95); border-radius: 8px; box-shadow: 0 6px 18px rgba(0,0,0,0.12);">
                            <a class="navbar-item" href="{{ route('profile.edit') }}"
                                style="color: #000000; transition: background 0.3s;">
                                Thông tin cá nhân
                            </a>
                            <a class="navbar-item" href="{{ route('orders.index') }}"
                                style="color: #000000; transition: background 0.3s;">
                                Đơn hàng của tôi
                            </a>
                            <a class="navbar-item" href="{{ route('collection.index') }}"
                                style="color: #000000; transition: background 0.3s;">
                                Bộ sưu tập của tôi
                            </a>
                            <hr class="navbar-divider" style="background-color: rgba(255,255,255,0.3);">

                            <a class="navbar-item" href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                style="color: #000000; transition: background 0.3s;">
                                Đăng xuất
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                @else
                    <div class="navbar-item">
                        <div class="buttons">
                            <a class="button is-primary" href="{{ route('register') }}"
                                style="border-radius: 6px; background: linear-gradient(90deg, #ff6b6b, #ff8e53); color: #fff;">
                                <strong>Đăng ký</strong>
                            </a>
                            <a class="button is-light" href="{{ route('login') }}"
                                style="border-radius: 6px; background-color: #fff; color: #ff6b6b; border: 1px solid #ff6b6b;">
                                Đăng nhập
                            </a>
                        </div>
                    </div>
                @endauth


                <a class="navbar-item" href="{{ route('cart.index') }}">
                    <span class="icon"><i class="fas fa-shopping-cart"></i></span>
                    <span>Giỏ hàng</span>
                    <span class="tag is-info" id="navCartCount" style="margin-left:5px">
                        {{ session('cart') ? count(session('cart')) : 0 }}
                    </span>
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
    // Logic cho Navbar Burger (Mobile)
    document.addEventListener("DOMContentLoaded", function() {
        var $burgers = Array.prototype.slice.call(document.querySelectorAll(".navbar-burger"), 0);
        if ($burgers.length > 0) {
            $burgers.forEach(function($el) {
                $el.addEventListener("click", function() {
                    var target = $el.dataset.target;
                    var $target = document.getElementById(target);
                    $el.classList.toggle("is-active");
                    if ($target) $target.classList.toggle("is-active");
                });
            });
        }
    });
</script>
