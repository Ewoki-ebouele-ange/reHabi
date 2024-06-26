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
            $table->foreignId('employe_id')->nullable()->constrained('employes')->onDelete('cascade');
            $table->foreignId('poste_id')->nullable()->constrained('postes')->onDelete('cascade');
            $table->timestamp('date_attribution')->nullable();
            $table->timestamp('date_suspension')->nullable();

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
