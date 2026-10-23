@extends('layouts.main')

@section('content')
<div class="page-content">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <div>
            <h4 class="mb-0"><i class="bx bx-bell me-1"></i> Mes notifications</h4>
            <small class="text-muted">{{ $nbNonLues }} non lue(s)</small>
        </div>

        <div class="d-flex gap-2">
            <div class="btn-group">
                <a href="{{ route('notifications.index') }}"
                   class="btn btn-sm {{ $filtre === 'non-lues' ? 'btn-outline-secondary' : 'btn-secondary' }}">Toutes</a>
                <a href="{{ route('notifications.index', ['filtre' => 'non-lues']) }}"
                   class="btn btn-sm {{ $filtre === 'non-lues' ? 'btn-secondary' : 'btn-outline-secondary' }}">Non lues</a>
            </div>

            @if ($nbNonLues > 0)
                <form method="POST" action="{{ route('notifications.tout-lire') }}" class="m-0">
                    @csrf
                    <button class="btn btn-sm btn-success">
                        <i class="bx bx-check-double"></i> Tout marquer comme lu
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card radius-10">
        <div class="list-group list-group-flush">
            @forelse ($notifications as $notif)
                @php
                    $couleurNotif = $notif->data['color'] ?? 'primary';
                    $iconeNotif   = $notif->data['icon'] ?? 'bx-bell';
                @endphp
                <div class="list-group-item d-flex gap-3 align-items-start py-3 {{ is_null($notif->read_at) ? 'bg-light' : '' }}">
                    <i class="bx {{ $iconeNotif }} fs-3 text-{{ $couleurNotif }}"></i>

                    <a href="{{ route('notifications.lire', $notif->id) }}" class="flex-grow-1 text-decoration-none text-body">
                        <div class="fw-semibold">
                            {{ $notif->data['title'] ?? 'Notification' }}
                            @if (is_null($notif->read_at))
                                <span class="badge bg-primary ms-1">Nouveau</span>
                            @endif
                        </div>
                        <div class="text-muted small">{{ $notif->data['message'] ?? '' }}</div>
                        <small class="text-secondary">
                            {{ $notif->created_at->format('d/m/Y H:i') }} · {{ $notif->created_at->diffForHumans() }}
                        </small>
                    </a>

                    <form method="POST" action="{{ route('notifications.supprimer', $notif->id) }}" class="m-0"
                          onsubmit="return confirm('Supprimer cette notification ?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-link text-danger" title="Supprimer">
                            <i class="bx bx-trash"></i>
                        </button>
                    </form>
                </div>
            @empty
                <div class="text-center p-5">
                    <i class="bx bx-bell-off fs-1 text-muted"></i>
                    <p class="text-muted mb-0 mt-2">Aucune notification.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-3">{{ $notifications->links() }}</div>
</div>
@endsection
