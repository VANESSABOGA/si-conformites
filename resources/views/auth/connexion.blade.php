<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion — Conformité YAKO AFRICA</title>
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/png">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    <style>
        body { min-height: 100vh; background: linear-gradient(135deg, #033f1f 0%, #046d38 100%); display: flex; align-items: center; }
        .carte { max-width: 420px; border: 0; border-radius: 14px; box-shadow: 0 20px 50px rgba(0,0,0,.25); }
        .btn-yako { background: #033f1f; color: #fff; }
        .btn-yako:hover { background: #046d38; color: #fff; }
        .bandeau { color: #c9a227; letter-spacing: 1px; font-size: 12px; text-transform: uppercase; }
    </style>
</head>
<body>
<div class="container">
    <div class="card carte mx-auto">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
                <img src="{{ asset('assets/images/logo-yako.png') }}" alt="YAKO AFRICA" style="max-height:60px"
                     onerror="this.style.display='none'">
                <div class="bandeau mt-3">Conformité & Gouvernance</div>
                <h4 class="mt-1 mb-0">Déclaration des conflits d'intérêts</h4>
                <small class="text-muted">Connectez-vous avec vos identifiants du SI-RH</small>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="email">Adresse email professionnelle</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus autocomplete="username">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Mot de passe</label>
                    <input id="password" type="password" name="password" class="form-control" required autocomplete="current-password">
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Rester connecté</label>
                </div>
                <button class="btn btn-yako w-100 py-2">Se connecter</button>
            </form>

            @if (config('conformite.sirh_url'))
                <div class="text-center mt-4">
                    <a class="small text-decoration-none" href="{{ rtrim(config('conformite.sirh_url'), '/') }}/forgot-password">
                        Mot de passe oublié ? (via le SI-RH)
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
</body>
</html>
