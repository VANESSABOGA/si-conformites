<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('declaration_conflits', function (Blueprint $table) {
            $table->enum('niveau_urgence', ['FAIBLE', 'MOYEN', 'ELEVE', 'CRITIQUE'])
                ->nullable()
                ->after('agent_denonce_fonction')
                ->comment("Niveau d'urgence de la dénonciation");

            $table->date('date_faits')
                ->nullable()
                ->after('niveau_urgence')
                ->comment('Date des faits constatés');

            $table->string('lieu_faits', 255)
                ->nullable()
                ->after('date_faits')
                ->comment('Lieu des faits constatés');

            $table->text('impact_estime')
                ->nullable()
                ->after('lieu_faits')
                ->comment('Impact estimé de la situation');

            $table->boolean('deja_signale')
                ->default(false)
                ->after('impact_estime')
                ->comment('Déjà signalé en interne');

            $table->string('signale_a', 255)
                ->nullable()
                ->after('deja_signale')
                ->comment('À qui la situation a été signalée');

            $table->json('temoins')
                ->nullable()
                ->after('signale_a')
                ->comment('Liste JSON des témoins');

            $table->boolean('protection_denonciateur')
                ->default(false)
                ->after('temoins')
                ->comment('Protection du lanceur d\'alerte demandée');

            $table->boolean('souhaite_anonyme')
                ->default(false)
                ->after('protection_denonciateur')
                ->comment('Anonymat demandé vis-à-vis de l\'agent');

            // Index
            $table->index('niveau_urgence', 'idx_conflits_niveau_urgence');
            $table->index('date_faits', 'idx_conflits_date_faits');
            $table->index('agent_denonce_id', 'idx_conflits_agent_denonce');
        });
    }

    public function down(): void
    {
        Schema::table('declaration_conflits', function (Blueprint $table) {
            $table->dropIndex('idx_conflits_niveau_urgence');
            $table->dropIndex('idx_conflits_date_faits');
            $table->dropIndex('idx_conflits_agent_denonce');

            $table->dropColumn([
                'niveau_urgence',
                'date_faits',
                'lieu_faits',
                'impact_estime',
                'deja_signale',
                'signale_a',
                'temoins',
                'protection_denonciateur',
                'souhaite_anonyme',
            ]);
        });
    }
};
