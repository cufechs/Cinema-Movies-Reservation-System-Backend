<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Movie;
use Faker\Generator as Faker;

$factory->define(Movie::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'poster_image' => 'https://cdn.al-ain.com/images/2021/11/10/127-105652-maxresdefault_700x400.jpg',
        'description' => $faker->text,
    ];
});
