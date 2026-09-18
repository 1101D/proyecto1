<ul class="main-menu" id="all-menu-items" role="menu">
    <li class="menu-title" role="presentation">Principal</li>
    <li class="slide">
        <a href="{{ url('/') }}" class="side-menu__item" role="menuitem">
            <span class="side_menu_icon"><i class="ri-home-2-line"></i></span>
            <span class="side-menu__label">Inicio</span>
        </a>
    </li>
    <li class="slide">
        <a href="#!" class="side-menu__item" role="menuitem">
            <span class="side_menu_icon"><i class="ri-calendar-event-line"></i></span>
            <span class="side-menu__label">Eventos</span>
            <i class="ri-arrow-down-s-line side-menu__angle"></i>
        </a>
        <ul class="slide-menu" role="menu">
            <li class="slide">
                <a href="{{ route('explore.index') }}" class="side-menu__item" role="menuitem">Explorar eventos</a>
            </li>
            <li class="slide">
                <a href="{{ route('events.index') }}" class="side-menu__item" role="menuitem">Mis eventos</a>
            </li>
            <li class="slide">
                <a href="{{ route('events.create') }}" class="side-menu__item" role="menuitem">Crear evento</a>
            </li>
            <li class="slide">
                <a href="{{ route('events.saved') }}" class="side-menu__item" role="menuitem">Eventos guardados</a>
            </li>
            <li class="slide">
                <a href="{{ route('tickets.index') }}" class="side-menu__item" role="menuitem">Mis entradas</a>
            </li>
            <li class="slide">
                <a href="{{ route('organizations.index') }}" class="side-menu__item" role="menuitem">Organizaciones</a>
            </li>
        </ul>
    </li>
    <li class="slide">
        <a href="#!" class="side-menu__item" role="menuitem">
            <span class="side_menu_icon"><i class="ri-user-line"></i></span>
            <span class="side-menu__label">Cuenta</span>
            <i class="ri-arrow-down-s-line side-menu__angle"></i>
        </a>
        <ul class="slide-menu" role="menu">
            <li class="slide">
                <a href="pages-profile-overview" class="side-menu__item" role="menuitem">Mi perfil</a>
            </li>
            <li class="slide">
                <a href="pages-profile-edit-overview" class="side-menu__item" role="menuitem">Configuración</a>
            </li>
        </ul>
    </li>
    @if (auth()->check() && auth()->user()->isAdmin())
        <li class="menu-title" role="presentation">Administración</li>
        <li class="slide">
            <a href="#!" class="side-menu__item" role="menuitem">
                <span class="side_menu_icon"><i class="ri-shield-user-line"></i></span>
                <span class="side-menu__label">Admin</span>
                <i class="ri-arrow-down-s-line side-menu__angle"></i>
            </a>
            <ul class="slide-menu" role="menu">
                <li class="slide">
                    <a href="{{ route('admin.dashboard') }}" class="side-menu__item" role="menuitem">Panel</a>
                </li>
                <li class="slide">
                    <a href="{{ route('admin.users.index') }}" class="side-menu__item" role="menuitem">Usuarios</a>
                </li>
                <li class="slide">
                    <a href="{{ route('admin.events.index') }}" class="side-menu__item" role="menuitem">Eventos</a>
                </li>
            </ul>
        </li>
    @endif
</ul>
