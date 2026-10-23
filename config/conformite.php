<?php

/*
|--------------------------------------------------------------------------
| Application Conformité — Conflits d'intérêts
|--------------------------------------------------------------------------
| Application autonome partageant la base "gestion_rh" avec le SI-RH.
*/

$liste = static fn (?string $valeur): array => array_values(array_filter(
    array_map('trim', explode(',', (string) $valeur))
));

$emails = static fn (?string $valeur): array => array_values(array_filter(
    $liste($valeur),
    static fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL)
));

return [

    // URL du SI-RH (lien "Retour au SI-RH", photos des agents).
    'sirh_url' => env('SIRH_URL'),

    // Matricules habilités au back-office Conformité (en plus des rôles ci-dessous).
    // Reprend la variable historique du SI-RH.
    'matricules_habilites' => $liste(env('CONFLIT_INTERET_MATRICULES', '')),

    // Rôles Spatie (table "roles" partagée) donnant accès au back-office.
    'roles_admin' => $liste(env('CONFORMITE_ROLES_ADMIN', 'admin,rh,conformite,compliance')),

    'notifications' => [
        'mail_actif'       => (bool) env('CONFORMITE_NOTIFICATIONS_MAIL', true),
        'roles_rh'         => $liste(env('SIRH_ROLES_RH', 'rh,admin')),
        'roles_conformite' => $liste(env('SIRH_ROLES_CONFORMITE', 'conformite,compliance')),
        'limite_cloche'    => (int) env('CONFORMITE_NOTIFICATIONS_CLOCHE', 8),
    ],

    'emails' => [
        'rh'             => $emails(env('SIRH_EMAILS_RH', '')),
        'conformite'     => $emails(env('SIRH_EMAILS_CONFORMITE', '')),
        'rediriger_vers' => env('MAIL_REDIRECT_TO'),
    ],
];
