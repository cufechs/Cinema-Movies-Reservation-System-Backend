<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovieReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moviereservations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->string('start_time');
            $table->string('end_time');
            $table->string('vacant_reserved_seats');
            $table->unsignedBigInteger('capacity');
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('movie_id');
            $table->foreign('movie_id')->references('id')->on('movies');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('moviereservations');
    }
}
