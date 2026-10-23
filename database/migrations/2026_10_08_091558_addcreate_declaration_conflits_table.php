<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('declaration_conflits', function (Blueprint $table) {
            $table->string('relation_type')->nullable();
            // $table->foreignId('relation_agent_id')->nullable()->constrained('agents');
            $table->unsignedBigInteger('relation_agent_id')->nullable();
            $table->string('relation_nom_externe')->nullable();

            $table->string('cadeau_type')->nullable();
            // $table->foreignId('cadeau_agent_id')->nullable()->constrained('agents');
            $table->unsignedBigInteger('cadeau_agent_id')->nullable();
            $table->string('cadeau_nom_externe')->nullable();

            $table->string('client_fournisseur_partenaire')->nullable();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('declaration_conflits', function (Blueprint $table) {
            $table->dropColumn(['relation_type', 'relation_agent_id', 'relation_nom_externe', 'cadeau_type', 'cadeau_agent_id', 'cadeau_nom_externe', 'client_fournisseur_partenaire']);
        });
    }
};
