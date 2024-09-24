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
    Schema::create('bookings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');  // Link to User
        $table->foreignId('trip_id')->constrained()->onDelete('cascade');  // Link to Trip
        $table->integer('seat_number');  // The seat number assigned to the user
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('bookings');
}

};
