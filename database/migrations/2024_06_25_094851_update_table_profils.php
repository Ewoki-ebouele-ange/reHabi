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
        Schema::table('profils', function (Blueprint $table) {
            $table->foreignId('application_id')->nullable()->constrained('applications')->onDelete('cascade');
            $table->timestamp('date_creation')->nullable();
            $table->timestamp('date_derniere_modification')->nullable();
            $table->timestamp('date_dernier_acces')->nullable();
            $table->timestamp('date_suppression')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profils', function (Blueprint $table) {
            //
        });
    }
};
