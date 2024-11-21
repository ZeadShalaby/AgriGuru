<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('temperature');
            $table->integer('humidity');
            $table->integer('light');
            $table->float('gas', 8, 2);  //? float with 8 digits, 2 after decimal            $table->integer('soil_moisture');
            $table->integer("soil_moisture");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_sensors');
    }
};
