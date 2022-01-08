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
            $table->timestamp('start_time')->default('1973-11-04 16:41:31');
            $table->timestamp('end_time')->default('1973-11-04 16:41:31');
            $table->string('vacant_reserved_seats');
            $table->unsignedBigInteger('capacity');
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('movie_id');
            $table->foreign('movie_id')->references('id')->on('movies');
            $table->timestamps();
        });

        Schema::create('user_moviereservation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('seat_no');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('movie_reservation_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('movie_reservation_id')->references('id')->on('moviereservations')->onDelete('cascade');
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
        Schema::dropIfExists('user_moviereservation');
    }
}
