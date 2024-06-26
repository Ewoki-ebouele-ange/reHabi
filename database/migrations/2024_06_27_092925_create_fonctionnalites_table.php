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
        Schema::create('fonctionnalites', function (Blueprint $table) {
            $table->id();
            $table->string('code_fonct')->unique();
            $table->string('libelle_fonct')->nullable();
            $table->foreignId('module_id')->nullable()->constrained('modules')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fonctionnalites');
    }
};
