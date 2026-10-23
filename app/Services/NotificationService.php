<?php

namespace App\Services;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notification as BaseNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;

/**
 * Point d'entrée unique pour envoyer les notifications SI-RH.
 *
 *  - Accepte des User, des Agent, des adresses email ou des collections.
 *  - Dédoublonne les destinataires.
 *  - N'interrompt JAMAIS le flux métier : toute erreur est journalisée.
 */
class NotificationService
{
    /**
     * Envoie une notification à un ou plusieurs destinataires.
     *
     * @param  mixed  $destinataires  User|Agent|string(email)|iterable de ceux-ci
     * @param  array  $exclureUserIds IDs utilisateurs à exclure (ex : l'auteur de l'action)
     * @return int    Nombre de destinataires notifiés
     */
    public function envoyer(mixed $destinataires, BaseNotification $notification, array $exclureUserIds = []): int
    {
        $envoyes = 0;

        // Ne pas envoyer si la transaction en cours est finalement annulée.
        if (method_exists($notification, 'afterCommit')) {
            $notification->afterCommit();
        }

        foreach ($this->resoudre($destinataires, $exclureUserIds) as $notifiable) {
            try {
                $notifiable->notify($notification);
                $envoyes++;
            } catch (\Throwable $e) {
                Log::error('NotificationService — échec d\'envoi', [
                    'notification' => get_class($notification),
                    'destinataire' => $notifiable instanceof User
                        ? "user#{$notifiable->id}"
                        : ($notifiable->routes['mail'] ?? 'inconnu'),
                    'erreur'       => $e->getMessage(),
                ]);
            }
        }

        return $envoyes;
    }

    /**
     * Utilisateurs de l'équipe RH (rôles définis dans config/sirh.php).
     * Les rôles absents de la base sont ignorés (évite RoleDoesNotExist).
     */
    public function equipeRh(): Collection
    {
        return $this->utilisateursAyantRoles(config('conformite.notifications.roles_rh', ['rh', 'admin']));
    }

    /**
     * Destinataires conformité : utilisateurs ayant un rôle conformité,
     * agents habilités par matricule (CONFLIT_INTERET_MATRICULES)
     * + boîtes génériques du .env.
     */
    public function equipeConformite(): Collection
    {
        $users = $this->utilisateursAyantRoles(config('conformite.notifications.roles_conformite', []));

        $matricules = config('conformite.matricules_habilites', []);
        if (! empty($matricules)) {
            $habilites = Agent::query()
                ->whereIn('matricule', $matricules)
                ->with('user')
                ->get()
                ->pluck('user')
                ->filter();

            $users = $users->merge($habilites);
        }

        return $users
            ->unique('id')
            ->values()
            ->concat(config('conformite.emails.conformite', []));
    }

    /**
     * Destinataires RH complets : utilisateurs RH + boîtes génériques.
     */
    public function destinatairesRh(): Collection
    {
        return $this->equipeRh()->concat(config('conformite.emails.rh', []));
    }

    /**
     * Adresse email la plus fiable d'un agent.
     */
    public function emailAgent(?Agent $agent): ?string
    {
        if (! $agent) {
            return null;
        }

        $candidats = [
            $agent->user?->email,
            $agent->contacts?->email_pro,
            $agent->email,
        ];

        foreach ($candidats as $email) {
            if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $email;
            }
        }

        return null;
    }

    /**
     * Supérieur hiérarchique (N+1) actif d'un agent, ou null.
     */
    public function superieur(Agent $agent): ?Agent
    {
        $superieur = $agent->affectationActuelle?->superieur;

        // Un "top" est son propre supérieur : on ne le notifie pas de ses propres demandes.
        if (! $superieur || (int) $superieur->id === (int) $agent->id) {
            return null;
        }

        return $superieur;
    }

    // ------------------------------------------------------------------
    //  Interne
    // ------------------------------------------------------------------

    private function utilisateursAyantRoles(array $roles): Collection
    {
        $roles = array_values(array_filter($roles));
        if (empty($roles)) {
            return collect();
        }

        try {
            $existants = Role::query()->whereIn('name', $roles)->pluck('name')->all();
        } catch (\Throwable $e) {
            Log::warning('NotificationService — lecture des rôles impossible', ['erreur' => $e->getMessage()]);
            return collect();
        }

        return empty($existants) ? collect() : User::role($existants)->get();
    }

    /**
     * Transforme l'entrée en liste de notifiables uniques.
     */
    private function resoudre(mixed $destinataires, array $exclureUserIds): Collection
    {
        $items = $destinataires instanceof Collection
            ? $destinataires
            : collect(is_iterable($destinataires) ? $destinataires : [$destinataires]);

        $users  = [];
        $emails = [];

        foreach ($items->flatten() as $item) {
            if ($item instanceof User) {
                $users[$item->id] = $item;
                continue;
            }

            if ($item instanceof Agent) {
                if ($item->user) {
                    $users[$item->user->id] = $item->user;
                } elseif ($email = $this->emailAgent($item)) {
                    $emails[strtolower($email)] = $email;
                }
                continue;
            }

            if (is_string($item) && filter_var(trim($item), FILTER_VALIDATE_EMAIL)) {
                $emails[strtolower(trim($item))] = trim($item);
            }
        }

        foreach ($exclureUserIds as $id) {
            unset($users[$id]);
        }

        // Une boîte générique déjà couverte par un utilisateur n'est pas notifiée deux fois.
        foreach ($users as $user) {
            unset($emails[strtolower((string) $user->email)]);
        }

        return collect(array_values($users))->concat(
            collect(array_values($emails))->map(fn ($email) => Notification::route('mail', $email))
        );
    }
}
