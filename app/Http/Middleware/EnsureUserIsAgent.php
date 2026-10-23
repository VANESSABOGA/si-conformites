<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** L'espace déclarant exige un compte rattaché à un agent du SI-RH. */
class EnsureUserIsAgent
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(
            $request->user()?->agent,
            403,
            "Votre compte n'est rattaché à aucun agent. Contactez la Direction des Ressources Humaines."
        );

        return $next($request);
    }
}
