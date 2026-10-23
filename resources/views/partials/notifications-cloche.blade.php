{{--
    Cloche des notifications applicatives (header principal + header onboarding).
    Variables optionnelles : $boutonClasse (classes du bouton).
--}}
@auth
    @php
        $limiteCloche  = config('conformite.notifications.limite_cloche', 8);
        $nbNonLues     = auth()->user()->unreadNotifications()->count();
        $dernieresNotifs = auth()->user()->notifications()->latest()->limit($limiteCloche)->get();
    @endphp

    <li class="nav-item dropdown dropdown-large" id="sirh-cloche">
        <div class="dropdown">
            <button class="{{ $boutonClasse ?? 'btn btn-light' }} position-relative" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                <i class="bx bx-bell fs-4"></i>
                <span id="sirh-cloche-badge"
                      class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ $nbNonLues ? '' : 'd-none' }}">
                    {{ $nbNonLues > 99 ? '99+' : $nbNonLues }}
                </span>
            </button>

            <div class="dropdown-menu dropdown-menu-end shadow p-0" style="width: 380px; max-width: 95vw;">
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                    <h6 class="mb-0">Notifications</h6>
                    @if ($nbNonLues > 0)
                        <form method="POST" action="{{ route('notifications.tout-lire') }}" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-link btn-sm p-0 text-decoration-none">
                                Tout marquer comme lu ({{ $nbNonLues }})
                            </button>
                        </form>
                    @endif
                </div>

                <div style="max-height: 400px; overflow-y: auto;">
                    @forelse ($dernieresNotifs as $notif)
                        @php
                            $couleurNotif = $notif->data['color'] ?? 'primary';
                            $iconeNotif   = $notif->data['icon'] ?? 'bx-bell';
                        @endphp
                        <a href="{{ route('notifications.lire', $notif->id) }}"
                           class="dropdown-item p-3 border-bottom text-wrap {{ is_null($notif->read_at) ? 'bg-light' : '' }}">
                            <div class="d-flex gap-3">
                                <div><i class="bx {{ $iconeNotif }} fs-4 text-{{ $couleurNotif }}"></i></div>
                                <div class="flex-grow-1" style="min-width: 0;">
                                    <div class="fw-semibold {{ is_null($notif->read_at) ? '' : 'text-muted' }}">
                                        {{ $notif->data['title'] ?? 'Notification' }}
                                    </div>
                                    <div class="small text-muted">{{ \Illuminate\Support\Str::limit($notif->data['message'] ?? '', 110) }}</div>
                                    <small class="text-secondary">{{ $notif->created_at->diffForHumans() }}</small>
                                </div>
                                @if (is_null($notif->read_at))
                                    <span class="mt-1 rounded-circle bg-primary flex-shrink-0" style="width:8px;height:8px;"></span>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="text-center p-4">
                            <i class="bx bx-bell-off fs-1 text-muted"></i>
                            <p class="text-muted mb-0 mt-2">Aucune notification</p>
                        </div>
                    @endforelse
                </div>

                <div class="text-center p-2 border-top">
                    <a href="{{ route('notifications.index') }}" class="small text-decoration-none">
                        Voir toutes les notifications
                    </a>
                </div>
            </div>
        </div>
    </li>

    @once
        <script>
            // Rafraîchit le compteur de la cloche toutes les 60 s (sans recharger la page).
            (function () {
                const badge = document.getElementById('sirh-cloche-badge');
                if (!badge || !window.fetch) return;

                setInterval(function () {
                    fetch(@json(route('notifications.resume')), {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        credentials: 'same-origin'
                    })
                        .then(function (r) { return r.ok ? r.json() : null; })
                        .then(function (data) {
                            if (!data) return;
                            badge.textContent = data.non_lues > 99 ? '99+' : data.non_lues;
                            badge.classList.toggle('d-none', data.non_lues === 0);
                        })
                        .catch(function () {});
                }, 60000);
            })();
        </script>
    @endonce
@endauth
