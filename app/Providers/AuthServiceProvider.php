<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot(): void
    {
        /**
         * Accès au back-office Conformité :
         *  - rôles Spatie listés dans config/conformite.php (roles_admin), ou
         *  - agent dont le matricule est habilité (CONFLIT_INTERET_MATRICULES).
         */
        Gate::define('administrer-conformite', function (User $user): bool {
            if ($user->hasAnyRole(config('conformite.roles_admin', []))) {
                return true;
            }

            $matricule = $user->agent?->matricule;

            return $matricule && in_array($matricule, config('conformite.matricules_habilites', []), true);
        });
    }
}
