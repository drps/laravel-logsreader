<?php

use App\Logs;
use Carbon\Carbon;
use Faker\Generator as Faker;

$logs = DB::select('select max(dt) maxdt FROM logs');
$startDatetime = Carbon::createFromFormat('Y-m-d H:i:s', $logs[0]->maxdt ?: date('Y-m-d H:i:s'));

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Logs::class, function (Faker $faker) use ($startDatetime) {
    return [
        'message' => $faker->sentence(3),
        'type' => $faker->numberBetween(1,10),
        'dt' => $startDatetime->addSeconds(TimeGenerator::getInstance()->add(random_int(1, 4))),
    ];
});
