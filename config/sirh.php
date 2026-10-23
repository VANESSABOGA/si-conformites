<?php

/*
|--------------------------------------------------------------------------
| SI-RH — Paramètres des notifications (emails + applicatives)
|--------------------------------------------------------------------------
|
| Toutes les adresses étaient auparavant codées en dur dans les contrôleurs
| (rh@yakoafrica.com, vanessa.boga@..., perol.kouletio@...). Elles sont
| désormais centralisées ici et pilotées par le .env.
|
*/

$liste = static fn (?string $valeur): array => array_values(array_filter(
    array_map('trim', explode(',', (string) $valeur)),
    static fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL)
));

return [

    // Application Conformité (conflits d'intérêts), externalisée du SI-RH.
    // Utilisée pour le menu, les redirections des anciennes URL et la cloche.
    'conformite_url' => env('CONFORMITE_URL'),

    'notifications' => [

        // Désactive uniquement le canal email (les notifications applicatives restent actives).
        'mail_actif' => (bool) env('SIRH_NOTIFICATIONS_MAIL', true),

        // Rôles Spatie considérés comme "équipe RH" (destinataires des workflows RH).
        // Les rôles inexistants en base sont ignorés automatiquement.
        'roles_rh' => array_values(array_filter(array_map('trim', explode(',', env('SIRH_ROLES_RH', 'rh,admin'))))),

        // Nombre de notifications affichées dans la cloche du header.
        'limite_cloche' => (int) env('SIRH_NOTIFICATIONS_CLOCHE', 8),
    ],

    'emails' => [
        // Boîtes génériques (séparées par des virgules dans le .env).
        'rh'          => $liste(env('SIRH_EMAILS_RH', '')),
        'contact_rh'  => $liste(env('SIRH_EMAIL_CONTACT_RH', env('SIRH_EMAILS_RH', ''))),

        // Copies du mail de bienvenue (onboarding).
        'bienvenue_cc'  => $liste(env('SIRH_EMAILS_BIENVENUE_CC', '')),
        'bienvenue_bcc' => $liste(env('SIRH_EMAILS_BIENVENUE_BCC', '')),

        // ⚠️ DEV / RECETTE UNIQUEMENT : si renseigné, TOUS les emails sont redirigés
        // vers cette adresse (remplace les adresses de test codées en dur).
        // Ignoré automatiquement en production.
        'rediriger_vers' => env('MAIL_REDIRECT_TO'),
    ],
];
