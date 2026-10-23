<?php

namespace App\Models\Concerns;

use LogicException;

/**
 * Modèle partagé avec le SI-RH, en LECTURE SEULE dans l'application Conformité.
 *
 * Les tables agents, affectations, contacts, directions… appartiennent au SI-RH :
 * c'est lui le "propriétaire" (source de vérité). La Conformité les lit pour
 * afficher et pré-remplir les déclarations, mais ne doit jamais les modifier,
 * sous peine de divergences entre les deux applications.
 */
trait LectureSeule
{
    public static function bootLectureSeule(): void
    {
        $bloquer = static function ($model): void {
            throw new LogicException(sprintf(
                '%s est en lecture seule dans l\'application Conformité (donnée gérée par le SI-RH).',
                class_basename($model)
            ));
        };

        static::creating($bloquer);
        static::updating($bloquer);
        static::deleting($bloquer);
    }
}
