<div class="align-items-center">

    <nav class="navbar navbar-expand-lg navbar-light bg-white w-100 p-2">
        <!-- Logo (aligned to the left) -->

        <img src="{{ url('logo') }}" class="img-fluid ps-5 me-5" alt="Logo">

        <div class="vr ms-5 align-self-center" style="height: 30px"></div>

        <div class="d-flex align-items-center justify-content-between w-100">

            <div class="d-flex">
                <img src="{{ url('back-icon') }}" class="img-fluid ps-5 me-5" alt="Logo">

                <!-- Navbar Menu (aligned to the right) -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto"> <!-- 'ms-auto' to align the links to the right -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.templates.index') ? 'active' : '' }}"
                                href="{{ route('admin.templates.index') }}">Email Template</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.placeholders.index') ? 'active' : '' }}"
                                href="{{ route('admin.placeholders.index') }}">Placeholder</a>
                        </li>
                    </ul>

                </div>
            </div>
            <img src="{{ url('logout-icon') }}" class="img-fluid ps-5 me-5" alt="logout-icon">
        </div>

    </nav>
</div>
