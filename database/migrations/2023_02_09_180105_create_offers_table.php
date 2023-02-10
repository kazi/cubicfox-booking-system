<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->integer('room_id')->nullable(false);
            $table->date('day')->nullable(false);
            $table->decimal('price', 10, 2)->nullable(false);
            $table->boolean('is_available')->default(true);
            $table->unique(['room_id', 'day']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('offers');
    }
}
