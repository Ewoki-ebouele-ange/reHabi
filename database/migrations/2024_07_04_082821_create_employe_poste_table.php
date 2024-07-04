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
        Schema::create('employe_poste', function (Blueprint $table) {
            $table->foreignId('employe_id')->constrained('employes')->onDelete('cascade');
            $table->foreignId('poste_id')->constrained('postes')->onDelete('cascade');
            $table->timestamp('date_debut_fonction')->nullable();
            $table->timestamp('date_fin_fonction')->nullable();
            $table->timestamps();

            $table->primary(['employe_id','poste_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employe_poste');
    }
};
