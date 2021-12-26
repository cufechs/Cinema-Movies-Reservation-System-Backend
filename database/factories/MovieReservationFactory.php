<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MovieReservation;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use App\Models\Movie;

$factory->define(MovieReservation::class, function (Faker $faker) {
    return [
        'date' => now(),
        'start_time' => $faker->time,
        'end_time' => $faker->time,
        'capacity' => $faker->randomDigit,
        'vacant_reserved_seats' => $faker->name,
        'price' => $faker->randomDigit,
        'movie_id' => Movie::all()->random()->id
    ];
});
