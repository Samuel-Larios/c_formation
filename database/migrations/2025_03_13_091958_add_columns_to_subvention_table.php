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
        Schema::table('subvention', function (Blueprint $table) {
            $table->string('start_up_kits_items_received')->nullable();
            $table->string('state_of_farm_location')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subvention', function (Blueprint $table) {
            $table->dropColumn(['start_up_kits_items_received', 'state_of_farm_location']);
        });
    }
};
