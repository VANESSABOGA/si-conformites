<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Aligne les migrations sur le schéma réellement utilisé par le code.
 *
 * Ces colonnes sont utilisées par les modèles (Campagne, DeclarationConflit)
 * mais n'étaient créées par AUCUNE migration du SI-RH (ajout manuel en base).
 * Sur la base de production existante : aucune modification (hasColumn).
 * Sur une installation neuve : les colonnes sont créées.
 *
 * Exécutée AVANT 2026_10_20_105207 qui s'appuie sur agent_denonce_fonction.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('declaration_conflits', function (Blueprint $table) {
            if (! Schema::hasColumn('declaration_conflits', 'agent_denonce_id')) {
                $table->unsignedBigInteger('agent_denonce_id')->nullable()->index();
            }
            if (! Schema::hasColumn('declaration_conflits', 'agent_denonce_nom')) {
                $table->string('agent_denonce_nom')->nullable();
            }
            if (! Schema::hasColumn('declaration_conflits', 'agent_denonce_fonction')) {
                $table->string('agent_denonce_fonction')->nullable();
            }
        });

        Schema::table('campagnes', function (Blueprint $table) {
            if (! Schema::hasColumn('campagnes', 'annee')) {
                $table->unsignedSmallInteger('annee')->nullable();
            }
            if (! Schema::hasColumn('campagnes', 'message')) {
                $table->text('message')->nullable();
            }
            if (! Schema::hasColumn('campagnes', 'cree_par')) {
                $table->unsignedBigInteger('cree_par')->nullable();
            }
            if (! Schema::hasColumn('campagnes', 'ouverte_le')) {
                $table->dateTime('ouverte_le')->nullable();
            }
            if (! Schema::hasColumn('campagnes', 'cloturee_le')) {
                $table->dateTime('cloturee_le')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Volontairement vide : ces colonnes existent en production
        // et ne doivent pas être supprimées par un rollback.
    }
};
