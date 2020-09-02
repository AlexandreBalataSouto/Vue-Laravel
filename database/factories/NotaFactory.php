<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Nota;
use Faker\Generator as Faker;

$factory->define(Nota::class, function (Faker $faker) {
    return [
        'nombre'        => $faker->sentence(),
        'descripcion'   => $faker->text(100)
    ];
});
