<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MovieReservation;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use App\Models\Movie;

$factory->define(MovieReservation::class, function (Faker $faker) {
    $randomDig = $faker->randomElement([20, 30]);
    $vancantSeats = '{"seats": [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]}';

    if ($randomDig == 30)
        $vancantSeats = '{"seats": [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]}';

    //$faker->randomElement(['customer', 'customer', 'customer', 'manager']),
    return [
        'date' => now(),
        'start_time' => $faker->dateTime,
        'end_time' => $faker->dateTime,
        'capacity' => $randomDig,
        'vacant_reserved_seats' => $vancantSeats,
        'price' => $faker->randomDigit,
        'movie_id' => Movie::all()->random()->id
    ];
});
