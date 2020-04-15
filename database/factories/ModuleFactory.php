<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Module;
use App\Tutor;
use Faker\Generator as Faker;

$factory->define(Module::class, function (Faker $faker) {
    return [
        'id' => \Illuminate\Support\Str::random(10),
        'name' => $faker->sentence(3),
        'lead_tutor_id' => factory(Tutor::class)->create()
    ];
});
