<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tutorial;
use \Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Tutorial::class, function (Faker $faker) {
    return [
        'module_id' => Str::random(10),
        'time_start' => now(),
        'time_end' => now(),
        'room' => Str::random(4)
    ];
});
