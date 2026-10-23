<?php

namespace App\Notifications;

use App\Models\Declaration;

/**
 * Notifications des déclarations de conflits d'intérêts / dénonciations.
 *
 * Événements :
 *  - soumise_declarant      → accusé de réception au déclarant
 *  - soumise_conformite     → nouvelle déclaration positive à examiner
 *  - modifiee_conformite    → déclaration modifiée par le déclarant
 *  - denonciation_declarant → accusé de réception au lanceur d'alerte
 *  - denonciation_conformite→ nouvelle dénonciation (anonymat respecté)
 *  - validee / rejetee      → décision communiquée au déclarant
 */
class DeclarationConflitNotification extends BaseNotification
{
    public const EVENEMENTS = [
        'soumise_declarant', 'soumise_conformite', 'modifiee_conformite',
        'denonciation_declarant', 'denonciation_conformite', 'validee', 'rejetee',
    ];

    public function __construct(public Declaration $declaration, public string $evenement)
    {
        if (! in_array($evenement, self::EVENEMENTS, true)) {
            throw new \InvalidArgumentException("Événement de déclaration inconnu : {$evenement}");
        }
    }

    private function versConformite(): bool
    {
        return str_ends_with($this->evenement, '_conformite');
    }

    private function estDenonciation(): bool
    {
        return str_starts_with($this->evenement, 'denonciation')
            || str_starts_with((string) $this->declaration->reference, 'DEN-');
    }

    /** Le lanceur d'alerte a demandé l'anonymat : son identité ne circule pas par email. */
    private function anonyme(): bool
    {
        return $this->estDenonciation() && (bool) $this->declaration->conflit?->souhaite_anonyme;
    }

    private function declarant(): string
    {
        return $this->anonyme() ? 'Anonyme (à la demande du déclarant)' : ($this->declaration->nom_prenoms ?: '—');
    }

    protected function type(): string
    {
        return 'conflit_' . $this->evenement;
    }

    protected function sousTitreMail(): string
    {
        return 'Conformité & Gouvernance';
    }

    protected function icone(): string
    {
        return match ($this->evenement) {
            'validee' => 'bx-check-shield',
            'rejetee' => 'bx-shield-x',
            default   => $this->estDenonciation() ? 'bx-error' : 'bx-shield-quarter',
        };
    }

    protected function couleur(): string
    {
        return match ($this->evenement) {
            'validee'                 => 'success',
            'rejetee'                 => 'danger',
            'denonciation_conformite' => 'danger',
            'soumise_conformite', 'modifiee_conformite' => 'warning',
            default                   => 'info',
        };
    }

    protected function titre(object $notifiable): string
    {
        return match ($this->evenement) {
            'soumise_declarant'       => 'Votre déclaration de conflit d\'intérêts a été enregistrée',
            'soumise_conformite'      => 'Nouvelle déclaration de conflit d\'intérêts à examiner',
            'modifiee_conformite'     => 'Déclaration de conflit d\'intérêts modifiée',
            'denonciation_declarant'  => 'Votre signalement a bien été transmis',
            'denonciation_conformite' => 'Nouveau signalement (dénonciation) reçu',
            'validee'                 => $this->estDenonciation() ? 'Votre signalement a été traité' : 'Votre déclaration de conflit d\'intérêts a été validée',
            'rejetee'                 => $this->estDenonciation() ? 'Votre signalement a été classé sans suite' : 'Votre déclaration de conflit d\'intérêts a été rejetée',
        };
    }

    protected function sujetMail(object $notifiable): string
    {
        return $this->titre($notifiable) . ' — ' . $this->declaration->reference;
    }

    protected function message(object $notifiable): string
    {
        return match ($this->evenement) {
            'soumise_declarant'       => 'Nous accusons réception de votre déclaration. Elle sera examinée par la Conformité ; vous serez informé(e) de la décision.',
            'soumise_conformite'      => "{$this->declarant()} a déclaré une situation de conflit d'intérêts qui nécessite votre examen.",
            'modifiee_conformite'     => "{$this->declarant()} a modifié sa déclaration de conflit d'intérêts.",
            'denonciation_declarant'  => 'Votre signalement a été transmis de manière confidentielle à la Conformité. Il sera traité dans le respect de la protection du déclarant.',
            'denonciation_conformite' => 'Un nouveau signalement a été déposé et doit être traité de manière strictement confidentielle.',
            'validee'                 => 'La Conformité a examiné et validé votre déclaration.',
            'rejetee'                 => 'La Conformité a examiné votre déclaration et l\'a rejetée.',
        };
    }

    protected function details(object $notifiable): array
    {
        $d = $this->declaration;

        $details = ['Référence' => $d->reference];

        if ($this->versConformite()) {
            $details['Déclarant'] = $this->declarant();
            if (! $this->anonyme()) {
                $details['Matricule'] = $d->matricule;
                $details['Direction'] = $d->direction;
            }
            $details['Nature']  = $d->conflit?->nature_conflit;
            if ($this->estDenonciation()) {
                $details['Niveau d\'urgence'] = $d->conflit?->niveau_urgence;
            }
        }

        $details['Date de soumission'] = $this->date($d->date_soumission, 'd/m/Y H:i');

        if ($this->evenement === 'rejetee') {
            $details['Motif du rejet'] = $d->motif_rejet;
        }

        return $details;
    }

    protected function texteAction(): string
    {
        return $this->versConformite() ? 'Examiner la déclaration' : 'Consulter ma déclaration';
    }

    protected function url(object $notifiable): ?string
    {
        return $this->versConformite()
            ? route('admin.conflits.show', $this->declaration)
            : route('conflits.show', $this->declaration);
    }

    protected function donnees(object $notifiable): array
    {
        return [
            'declaration_id' => $this->declaration->id,
            'reference'      => $this->declaration->reference,
        ];
    }
}
