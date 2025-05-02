<!-- resources/views/partials/navbar.blade.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fs-4 fw-blod" href="{{ url('/') }}">{{ $companyName }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('user-list') ? 'active' : '' }}"
                        href="{{ url('/user-list') }}">Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('equipment-list') ? 'active' : '' }}"
                        href="{{ url('/equipment-list') }}">Equipments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('product-list') ? 'active' : '' }}"
                        href="{{ url('/product-list') }}">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('create-categroy') ? 'active' : '' }}"
                        href="{{ url('/create-categroy') }}">Add Category</a>
                </li>
            </ul>

            @auth
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="" onclick="">
                            Logout
                        </a>
                        <form id="logout-form" action="" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            @else
                <ul class="navbar-nav">
                    <li class="nav-item {{ request()->is('login') ? 'active' : '' }}">
                        <a class="nav-link" id="logout" >LogOut</a>
                    </li>
                    {{-- <li class="nav-item {{ request()->is('login') ? 'active' : '' }}">
                        <a class="nav-link " href="{{ url('/login') }}">Login</a>
                    </li>
                    <li class="nav-item {{ request()->is('userCreate') ? 'active' : '' }}">
                        <a class="nav-link " href="{{ url('/userCreate') }}">Register</a>
                    </li> --}}
                </ul>
            @endauth
        </div>
    </div>
</nav>
