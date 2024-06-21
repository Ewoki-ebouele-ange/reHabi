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
        Schema::create('fonctionnalite_profil', function (Blueprint $table) {
            $table->foreignId('fonctionnalite_id')->constrained('fonctionnalites')->onDelete('cascade');
            $table->foreignId('profil_id')->constrained('profils')->onDelete('cascade');

            $table->primary(['fonctionnalite_id','profil_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fonctionnalite_profil');
    }
};