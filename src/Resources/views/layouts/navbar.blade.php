<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <img src="{{ url('logo') }}" class="img-fluid ms-lg-5 me-lg-5 ms-md-0 me-md-0 ms-sm-0 me-sm-0 " alt="Logo">


        <div class="vr ms-5 align-self-center" style="height: 30px"></div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>


        <div class="back-icon">
            <a class="nav-link" href={{ route(config('email-templates.fallbackUrl')) }}>
                <img src="{{ url('back-icon') }}" class="img-fluid ps-5 me-5" alt="Logo">
               </a>
        </div>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.templates.*') ? 'active' : '' }}"
                        href="{{ route('admin.templates.index') }}">Email Template</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.placeholders.index') ? 'active' : '' }}"
                        href="{{ route('admin.placeholders.index') }}">Placeholder</a>
                </li>


            </ul>

        </div>

        <div class="logout-icon">
            <img src="{{ url('logout-icon') }}" class="img-fluid ps-5 me-5" alt="logout-icon">
        </div>
    </div>
</nav>


