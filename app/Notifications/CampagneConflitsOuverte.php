<?php

namespace App\Notifications;

use App\Models\Campagne;

/** → tous les agents actifs : ouverture d'une campagne de déclaration. */
class CampagneConflitsOuverte extends BaseNotification
{
    public function __construct(public Campagne $campagne) {}

    protected function type(): string { return 'campagne_conflits_ouverte'; }
    protected function icone(): string { return 'bx-broadcast'; }
    protected function couleur(): string { return 'warning'; }
    protected function sousTitreMail(): string { return 'Conformité & Gouvernance'; }
    protected function texteAction(): string { return 'Faire ma déclaration'; }

    protected function titre(object $notifiable): string
    {
        return "Campagne de déclaration des conflits d'intérêts ouverte";
    }

    protected function sujetMail(object $notifiable): string
    {
        return "Déclaration de conflits d'intérêts — {$this->campagne->libelle}";
    }

    protected function message(object $notifiable): string
    {
        return $this->campagne->message
            ?: "La campagne « {$this->campagne->libelle} » est ouverte. Chaque collaborateur doit compléter sa déclaration de conflits d'intérêts.";
    }

    protected function details(object $notifiable): array
    {
        return [
            'Campagne'         => $this->campagne->libelle,
            'Année'            => $this->campagne->annee,
            'Date d\'ouverture' => $this->date($this->campagne->date_ouverture),
            'Date de clôture'  => $this->date($this->campagne->date_cloture),
        ];
    }

    protected function lignes(object $notifiable): array
    {
        return ['Merci de procéder à votre déclaration dans les délais, même si vous n\'avez aucun conflit à signaler.'];
    }

    protected function url(object $notifiable): ?string
    {
        return route('conflits.create');
    }

    protected function donnees(object $notifiable): array
    {
        return ['campagne_id' => $this->campagne->id];
    }
}
