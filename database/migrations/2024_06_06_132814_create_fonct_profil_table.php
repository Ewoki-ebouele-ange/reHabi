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
        Schema::create('fonct_profil', function (Blueprint $table) {
            $table->foreignId('fonct_id')->constrained('fonctionnalites')->onDelete('cascade');
            $table->foreignId('profil_id')->constrained('profils')->onDelete('cascade');

            $table->primary(['fonct_id','profil_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fonct_profil');
    }
};
