<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Socle commun de toutes les notifications SI-RH.
 *
 *  - Canal "database" (cloche applicative) + canal "mail" (si une adresse existe).
 *  - Payload applicatif normalisé : title, message, icon, color, url, type.
 *  - Email au gabarit YAKO AFRICA (resources/views/emails/notification.blade.php).
 *
 * Les classes filles n'ont qu'à décrire le contenu (titre, message, détails, lien).
 *
 * ShouldQueue : avec QUEUE_CONNECTION=sync l'envoi reste immédiat ; avec
 * QUEUE_CONNECTION=database les emails partent en arrière-plan (recommandé).
 * Chaque canal est un job distinct : un échec SMTP n'empêche pas la
 * notification applicative d'être enregistrée.
 */
abstract class BaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /** Nombre de tentatives en cas d'échec (mode queue). */
    public int $tries = 3;

    // ------------------------------------------------------------------
    //  À définir par les classes filles
    // ------------------------------------------------------------------

    abstract protected function titre(object $notifiable): string;

    abstract protected function message(object $notifiable): string;

    abstract protected function url(object $notifiable): ?string;

    /** Identifiant fonctionnel (filtrage, statistiques). */
    abstract protected function type(): string;

    // ------------------------------------------------------------------
    //  Personnalisables
    // ------------------------------------------------------------------

    /** Icône Boxicons affichée dans la cloche. */
    protected function icone(): string
    {
        return 'bx-bell';
    }

    /** Couleur Bootstrap (primary, success, warning, danger, info…). */
    protected function couleur(): string
    {
        return 'primary';
    }

    /** Objet de l'email (par défaut : le titre). */
    protected function sujetMail(object $notifiable): string
    {
        return $this->titre($notifiable);
    }

    /** Tableau "libellé => valeur" affiché dans l'email. */
    protected function details(object $notifiable): array
    {
        return [];
    }

    /** Lignes de texte complémentaires dans l'email. */
    protected function lignes(object $notifiable): array
    {
        return [];
    }

    /** Libellé du bouton d'action dans l'email. */
    protected function texteAction(): string
    {
        return 'Consulter sur le SI-RH';
    }

    /** Sous-titre du bandeau vert de l'email. */
    protected function sousTitreMail(): string
    {
        return 'Système d\'Information RH';
    }

    /** Permet de désactiver l'email pour une notification purement applicative. */
    protected function envoyerParMail(object $notifiable): bool
    {
        return true;
    }

    /** Données métier additionnelles stockées dans la notification applicative. */
    protected function donnees(object $notifiable): array
    {
        return [];
    }

    // ------------------------------------------------------------------
    //  Mécanique Laravel
    // ------------------------------------------------------------------

    public function via(object $notifiable): array
    {
        $canaux = [];

        // Un destinataire "à la volée" (Notification::route) n'a pas de table notifications.
        if (! $notifiable instanceof AnonymousNotifiable) {
            $canaux[] = 'database';
        }

        if (
            config('conformite.notifications.mail_actif', true)
            && $this->envoyerParMail($notifiable)
            && filled($notifiable->routeNotificationFor('mail', $this))
        ) {
            $canaux[] = 'mail';
        }

        return $canaux;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $nom = $this->nomDestinataire($notifiable);

        return (new MailMessage)
            ->subject($this->sujetMail($notifiable))
            ->view('emails.notification', [
                'headerSubtitle' => $this->sousTitreMail(),
                'titre'          => $this->titre($notifiable),
                'salutation'     => $nom ? "Bonjour {$nom}," : 'Bonjour,',
                'introduction'   => $this->message($notifiable),
                'lignes'         => $this->lignes($notifiable),
                'details'        => $this->normaliserDetails($this->details($notifiable)),
                'actionUrl'      => $this->url($notifiable),
                'actionTexte'    => $this->texteAction(),
                'couleur'        => $this->couleur(),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return array_merge($this->donnees($notifiable), [
            'title'   => $this->titre($notifiable),
            'message' => $this->message($notifiable),
            'icon'    => $this->icone(),
            'color'   => $this->couleur(),
            'url'     => $this->url($notifiable),
            'type'    => $this->type(),
        ]);
    }

    // ------------------------------------------------------------------
    //  Helpers
    // ------------------------------------------------------------------

    /**
     * Valeurs affichables dans l'email : tableaux aplatis (ex. natures de conflit
     * castées en array), valeurs vides retirées.
     */
    protected function normaliserDetails(array $details): array
    {
        $resultat = [];

        foreach ($details as $libelle => $valeur) {
            if (is_array($valeur)) {
                $valeur = implode(', ', array_map(
                    static fn ($v) => ucfirst(mb_strtolower(str_replace('_', ' ', (string) $v))),
                    array_filter($valeur, 'is_scalar')
                ));
            } elseif ($valeur instanceof \BackedEnum) {
                $valeur = (string) $valeur->value;
            } elseif (is_bool($valeur)) {
                $valeur = $valeur ? 'Oui' : 'Non';
            }

            if ($valeur !== null && $valeur !== '') {
                $resultat[$libelle] = (string) $valeur;
            }
        }

        return $resultat;
    }

    protected function nomDestinataire(object $notifiable): ?string
    {
        if ($notifiable instanceof AnonymousNotifiable) {
            return null;
        }

        $agent = method_exists($notifiable, 'agent') ? $notifiable->agent : null;

        if ($agent) {
            return trim(($agent->prenom ?? '') . ' ' . ($agent->nom ?? '')) ?: null;
        }

        return $notifiable->name ?? null;
    }

    protected function date($valeur, string $format = 'd/m/Y'): ?string
    {
        if (! $valeur) {
            return null;
        }

        return $valeur instanceof \DateTimeInterface
            ? $valeur->format($format)
            : \Illuminate\Support\Carbon::parse($valeur)->format($format);
    }

    /** Le destinataire fait-il partie de l'équipe RH ? */
    protected function estRh(object $notifiable): bool
    {
        return method_exists($notifiable, 'hasAnyRole')
            && $notifiable->hasAnyRole(config('conformite.notifications.roles_rh', ['rh', 'admin']));
    }

    protected function nomAgent($agent): string
    {
        return $agent ? trim(($agent->prenom ?? '') . ' ' . ($agent->nom ?? '')) : '—';
    }
}
