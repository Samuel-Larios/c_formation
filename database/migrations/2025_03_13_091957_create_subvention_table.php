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
        Schema::create('subvention', function (Blueprint $table) {
            $table->id();
            $table->string('start_up_kits')->nullable();
            $table->string('grants')->nullable();
            $table->string('loan')->nullable();
            $table->string('date')->nullable();
            $table->string('start_up_kits_items_received')->nullable();
            $table->string('state_of_farm_location')->nullable();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('site_id')->constrained('sites')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subvention');
    }
};
