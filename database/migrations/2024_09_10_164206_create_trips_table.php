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
    Schema::create('trips', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('description');
        $table->string('start_location');
        $table->string('destination');
        $table->time('trip_time');
        $table->enum('status', ['new', 'processing', 'cancelled', 'done'])->default('new');
        $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');  // Link to Vehicle
        $table->float('price');
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('trips');
}

};
