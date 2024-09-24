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
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User foreign key
            $table->foreignId('trip_id')->constrained()->onDelete('cascade'); // Trip foreign key
            $table->integer('seat_number');
            $table->string('unique_identifier', 7); // Unique identifier
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
    
};
