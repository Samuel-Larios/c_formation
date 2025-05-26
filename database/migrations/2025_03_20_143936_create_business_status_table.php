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
        Schema::create('business_status', function (Blueprint $table) {
            $table->id();
            $table->string('type_of_business');
            $table->string('status');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('site_id')->constrained('sites')->onDelete('cascade'); // Ajouter cette ligne
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_status');
    }
};
