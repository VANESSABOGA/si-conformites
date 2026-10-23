<div class="sidebar-wrapper" style="background-color: #033f1f; color: white!important;" data-simplebar="true">

    <div class="sidebar-header d-flex align-items-center justify-content-between px-3">
        <div>
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/images/logo-yako.png') }}" width="60" class="logo-icon" alt="Logo Yako">
            </a>
        </div>
        <div>
            <h4 class="logo-text fw-bold mb-0" style="font-size: 15px; color:#c9a227;">CONFORMITÉ</h4>
        </div>
        <div class="toggle-icon ms-auto" id="sidebar-toggle" style="cursor:pointer;">
            <i class='bx bx-menu'></i>
        </div>
    </div>

    @php
        $estAgent = (bool) Auth::user()?->agent;

        $estConformite = Auth::user()?->can('administrer-conformite');
    @endphp

    <!-- {{dd( var_dump(Auth::user()?->agent);)}} -->
    <ul class="metismenu" id="menu">

        @if ($estAgent)
            <li class="menu-label">Mon espace</li>
            <li>
                <a href="{{ route('conflits.index') }}">
                    <div class="parent-icon"><i class="bx bx-shield-quarter text-white"></i></div>
                    <div class="menu-title" style="color:#fff">Mes déclarations</div>
                </a>
            </li>
            <li>
                <a href="{{ route('conflits.create') }}">
                    <div class="parent-icon"><i class="bx bx-edit text-white"></i></div>
                    <div class="menu-title" style="color:#fff">Nouvelle déclaration</div>
                </a>
            </li>
            <li>
                <a href="{{ route('conflits.denonciation.create') }}">
                    <div class="parent-icon"><i class="bx bx-error text-white"></i></div>
                    <div class="menu-title" style="color:#fff">Signaler une situation</div>
                </a>
            </li>
        @endif

        @if ($estConformite)
            <li class="menu-label">administration Conflit</li>
            <li>
                <a href="{{ route('admin.conflits.index') }}">
                    <div class="parent-icon"><i class="bx bx-shield-check text-white"></i></div>
                    <div class="menu-title" style="color:#fff">Déclarations reçues</div>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.conflits.campagnes.index') }}">
                    <div class="parent-icon"><i class="bx bx-megaphone text-white"></i></div>
                    <div class="menu-title" style="color:#fff">Campagnes</div>
                </a>
            </li>
        @endif

        <li class="menu-label">Général</li>
        <li>
            <a href="{{ route('notifications.index') }}">
                <div class="parent-icon"><i class="bx bx-bell text-white"></i></div>
                <div class="menu-title" style="color:#fff">Notifications</div>
            </a>
        </li>
        @if (config('conformite.sirh_url'))
            <li>
                <a href="{{ config('conformite.sirh_url') }}">
                    <div class="parent-icon"><i class="bx bx-arrow-back text-white"></i></div>
                    <div class="menu-title" style="color:#fff">Retour au SI-RH</div>
                </a>
            </li>
        @endif

        <li class="menu-label">Session</li>
        <li>
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <div class="parent-icon"><i class="bx bx-log-out-circle text-danger"></i></div>
                <div class="menu-title text-danger">Déconnexion</div>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </li>
    </ul>
</div>
