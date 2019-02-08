<?php

use App\Logs;
use Carbon\Carbon;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Logs::class, function (Faker $faker) {
    return [
        'message' => $faker->sentence(3),
        'type' => $faker->numberBetween(1,10),
        'dt' => Carbon::now()->addSeconds(TimeGenerator::getInstance()->add(random_int(1, 20))),
    ];
});
