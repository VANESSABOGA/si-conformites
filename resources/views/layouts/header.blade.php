<header>
    <div class="topbar d-flex align-items-center">
        <nav class="navbar navbar-expand gap-3">
            <div class="mobile-toggle-menu"><i class='bx bx-menu'></i></div>

            <div class="d-none d-md-block fw-semibold" style="color:#033f1f;">
                <i class="bx bx-shield-quarter"></i> Déclaration des conflits d'intérêts
            </div>

            <div class="top-menu ms-auto">
                <ul class="navbar-nav align-items-center gap-1">
                    @include('partials.notifications-cloche')
                </ul>
            </div>

            @php
                $utilisateur = Auth::user();
                print_r($utilisateur);
                $agentConnecte = $utilisateur?->agent;
                $nomAffiche = $agentConnecte ? trim($agentConnecte->prenom . ' ' . $agentConnecte->nom) : $utilisateur?->name;
                $initiales = collect(explode(' ', (string) $nomAffiche))->filter()->take(2)
                    ->map(fn ($m) => mb_strtoupper(mb_substr($m, 0, 1)))->implode('');
            @endphp

            <div class="user-box dropdown px-3">
                <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret"
                   href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-light shadow-sm"
                         style="width: 40px; height: 40px; overflow: hidden;">
                        @if ($agentConnecte?->photo_url)
                            <img src="{{ $agentConnecte->photo_url }}" alt="" class="rounded-circle"
                                 style="width:100%;height:100%;object-fit:cover;"
                                 onerror="this.remove()">
                        @endif
                        <span class="text-primary fw-bold">{{ $initiales }}</span>
                    </div>
                    <div class="user-info">
                        <p class="user-name mb-0">{{ $nomAffiche }}</p>
                        <p class="designattion mb-0">
                            {{ $utilisateur?->can('administrer-conformite') ? 'Conformité' : ($agentConnecte?->matricule ?? 'Utilisateur') }}
                        </p>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('notifications.index') }}"><i class="bx bx-bell"></i> Mes notifications</a></li>
                    @if (config('conformite.sirh_url'))
                        <li><a class="dropdown-item" href="{{ config('conformite.sirh_url') }}"><i class="bx bx-arrow-back"></i> Retour au SI-RH</a></li>
                    @endif
                    <li><div class="dropdown-divider mb-0"></div></li>
                    <li>
                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bx bx-log-out-circle"></i> Déconnexion
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
