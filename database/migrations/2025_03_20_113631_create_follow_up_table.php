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
        Schema::create('follow_up', function (Blueprint $table) {
            $table->id();
            $table->string('farm_visits'); // Visites à la ferme
            $table->string('phone_contact'); // Contact téléphonique
            $table->string('sharing_of_impact_stories'); // Partage d'histoires d'impact
            $table->string('back_stopping'); //
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
        Schema::dropIfExists('follow_up');
    }
};
