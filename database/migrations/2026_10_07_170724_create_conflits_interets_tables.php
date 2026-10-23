<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ============================================================
        // 1. CAMPAGNES
        // ============================================================
        Schema::create('campagnes', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('code_campagne', 50)->unique();
            $table->string('libelle', 200);
            $table->dateTime('date_ouverture');
            $table->dateTime('date_cloture');
            $table->enum('statut', ['PREPARATION', 'OUVERTE', 'CLOTUREE', 'ARCHIVEE'])->default('PREPARATION');
            $table->string('proprietaire_traitement', 150)->nullable();
            $table->string('direction_pilote', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('statut');
            $table->index(['date_ouverture', 'date_cloture']);
        });

        // ============================================================
        // 2. DÉCLARATIONS
        // ============================================================
        Schema::create('declarations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('reference', 50)->unique()->nullable();

            // $table->foreignId('id_campagne')->constrained('campagnes')->cascadeOnDelete();
            // $table->foreignId('id_collaborateur')->constrained('agents')->cascadeOnDelete();

            $table->unsignedBigInteger('id_campagne')->nullable();
            $table->unsignedBigInteger('id_collaborateur')->nullable();
            $table->string('matricule', 20);
            $table->string('nom_prenoms', 250);
            $table->string('direction', 150)->nullable();
            $table->string('departement', 150)->nullable();
            $table->string('fonction', 150)->nullable();
            $table->string('email_pro', 150)->nullable();
            $table->string('nom_n1', 250)->nullable();

            $table->enum('reponse_q1', ['OUI', 'NON']);
            $table->enum('statut', ['BROUILLON', 'SOUMISE', 'MODIFIEE', 'VALIDEE', 'REJETEE'])->default('SOUMISE');

            $table->dateTime('date_soumission')->nullable();
            $table->dateTime('date_derniere_modif')->nullable();
            $table->dateTime('date_validation')->nullable();
            $table->dateTime('date_rejet')->nullable();

            // $table->foreignId('validee_par')->nullable()->constrained('users')->nullOnDelete();
            // $table->foreignId('rejetee_par')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('validee_par')->nullable();
            $table->unsignedBigInteger('rejetee_par')->nullable();
            $table->text('motif_rejet')->nullable();

            $table->boolean('accuse_reception_genere')->default(false);
            $table->dateTime('date_accuse_reception')->nullable();
            $table->string('accuse_reception_numero', 50)->nullable();

            $table->string('ip_soumission', 45)->nullable();
            $table->string('user_agent', 500)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('matricule');
            $table->index('reponse_q1');
            $table->index('statut');
            $table->index('date_soumission');
            $table->unique(['id_campagne', 'id_collaborateur'], 'uniq_campagne_collab');
        });

        // ============================================================
        // 3. DÉCLARATION CONFLITS
        // ============================================================
        Schema::create('declaration_conflits', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('id_declaration')->constrained('declarations')->cascadeOnDelete();

            $table->unsignedBigInteger('id_declaration')->nullable();


            $table->json('nature_conflit');
            $table->text('description_detaillee');
            $table->text('personne_societe_concernee')->nullable();
            $table->date('date_debut_situation')->nullable();
            $table->enum('influence_decisions', ['OUI', 'NON']);
            $table->text('mesures_prises')->nullable();
            $table->timestamps();
            $table->index('id_declaration');
        });

        // ============================================================
        // 4. DÉCLARATION HONNEURS
        // ============================================================
        Schema::create('declaration_honneurs', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('id_declaration')->constrained('declarations')->cascadeOnDelete();
            $table->unsignedBigInteger('id_declaration')->nullable();
            $table->boolean('certification_honneur')->default(false);
            $table->boolean('engagement_signalement')->default(false);
            $table->dateTime('date_certification')->nullable();
            $table->timestamps();
            $table->index('id_declaration');
        });

        // ============================================================
        // 5. CONSENTEMENTS
        // ============================================================
        Schema::create('consentements', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('id_declaration')->constrained('declarations')->cascadeOnDelete();
            $table->unsignedBigInteger('id_declaration')->nullable();
            $table->boolean('lecture_notice')->default(false);
            $table->boolean('autorisation_traitement')->default(false);
            $table->boolean('engagement_declarant')->default(false);
            $table->dateTime('date_consentement')->nullable();
            $table->string('version_notice', 20)->default('1.0');
            $table->timestamps();
            $table->index('id_declaration');
        });

        // ============================================================
        // 6. DÉCLARATION HISTORIQUES
        // ============================================================
        Schema::create('declaration_historiques', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('id_declaration')->constrained('declarations')->cascadeOnDelete();
            $table->unsignedBigInteger('id_declaration')->nullable();
            $table->string('ancien_statut', 30)->nullable();
            $table->string('nouveau_statut', 30)->nullable();
            $table->string('action', 150);
            $table->text('commentaire')->nullable();
            // $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
            $table->index('id_declaration');
        });

        // ============================================================
        // 7. STATUT PARTICIPATIONS
        // ============================================================
        Schema::create('statut_participations', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('id_campagne')->constrained('campagnes')->cascadeOnDelete();
            // $table->foreignId('id_collaborateur')->constrained('agents')->cascadeOnDelete();
            $table->unsignedBigInteger('id_campagne')->nullable();
            $table->unsignedBigInteger('id_collaborateur')->nullable();
            $table->boolean('a_declare')->default(false);
            $table->dateTime('date_declaration')->nullable();
            $table->enum('reponse', ['OUI', 'NON'])->nullable();
            $table->boolean('relance_envoyee')->default(false);
            $table->unsignedInteger('nb_relances')->default(0);
            $table->timestamps();
            $table->unique(['id_campagne', 'id_collaborateur'], 'uniq_participation_campagne_collab');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statut_participations');
        Schema::dropIfExists('declaration_historiques');
        Schema::dropIfExists('consentements');
        Schema::dropIfExists('declaration_honneurs');
        Schema::dropIfExists('declaration_conflits');
        Schema::dropIfExists('declarations');
        Schema::dropIfExists('campagnes');
    }
};
