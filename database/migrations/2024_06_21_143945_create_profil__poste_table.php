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
        Schema::create('profil__poste', function (Blueprint $table) {
            $table->foreignId('profil_id')->constrained('profils')->onDelete('cascade');
            $table->foreignId('poste_id')->constrained('postes')->onDelete('cascade');

            $table->primary(['profil_id','poste_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil__poste');
    }
};
