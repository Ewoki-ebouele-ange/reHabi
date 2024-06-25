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
        Schema::create('employe_profil', function (Blueprint $table) {
            $table->foreignId('employe_id')->constrained('employes')->onDelete('cascade');
            $table->foreignId('profil_id')->constrained('profils')->onDelete('cascade');
            $table->timestamp('date_debut')->nullable();
            $table->timestamp('date_fin')->nullable();

            $table->primary(['employe_id','profil_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employe_profil');
    }
};
