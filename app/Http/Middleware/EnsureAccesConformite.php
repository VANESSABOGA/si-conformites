<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Back-office : réservé aux profils autorisés par le Gate "administrer-conformite". */
class EnsureAccesConformite
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(
            $request->user()?->can('administrer-conformite'),
            403,
            "Accès réservé à la Conformité."
        );

        return $next($request);
    }
}
