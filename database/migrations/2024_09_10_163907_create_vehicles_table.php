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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('vehicle_model');
            $table->string('plate_number')->unique();
            $table->integer('capacity');  // Total seats in the vehicle
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
    
};
