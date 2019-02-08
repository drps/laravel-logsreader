<?php

use App\Logs;
use Faker\Generator as Faker;


/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Logs::class, function (Faker $faker, $startDatetime) {
    $from = $startDatetime[0];
    return [
        'message' => $faker->sentence(3),
        'type' => $faker->numberBetween(1,10),
        'dt' => $from,
    ];
});
