<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tutor;
use \Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Tutor::class, function (Faker $faker) {
    return [
        'id' => Str::random(4),
        'name' => $faker->name,
        'room' => Str::random(4),
        'email' => $faker->safeEmail
    ];
});
