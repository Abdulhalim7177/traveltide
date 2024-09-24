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
    Schema::dropIfExists('bookings');
}

public function down()
{
    // Optionally, you could recreate the table here if you need rollback functionality
}


    /**
     * Reverse the migrations.
     */

};
