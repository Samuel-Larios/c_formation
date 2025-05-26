<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('sexe');
            $table->string('situation_matrimoniale')->nullable();
            $table->string('situation_handicape')->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('contact')->nullable();
            $table->string('contact_pers1')->nullable();
            $table->string('contact_pers2')->nullable();
            $table->string('contact_pers3')->nullable();
            $table->string('contact_pers4')->nullable();
            $table->string('contact_pers5')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->string('state_of_origin');
            $table->string('state_of_residence');
            // 13.Farm location
            $table->string('state')->nullable();
            $table->string('lga')->nullable();
            $table->string('community')->nullable();
            $table->foreignId('site_id')->constrained('sites')->onDelete('cascade');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
