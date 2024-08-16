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
        Schema::create('poste_profil', function (Blueprint $table) {
            $table->foreignId('poste_id')->constrained('postes')->onDelete('cascade');
            $table->foreignId('profil_id')->constrained('profils')->onDelete('cascade');
            $table->timestamps();

            $table->primary(['poste_id','profil_id']);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poste_profil');
    }
};
