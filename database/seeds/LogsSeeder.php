<?php

use App\Logs;
use Illuminate\Database\Seeder;

class LogsSeeder extends Seeder
{
    public function run()
    {
        $count = 0;
        for ($j = 0; $j < 10; $j++) {
            for ($i = 0; $i < 10; $i++) {
                $logs = factory(Logs::class, 20000)->make()->all();
                $chunksize = 100;

                foreach (array_chunk($logs, $chunksize) as $chunk) {
                    $data = array();
                    foreach ($chunk as $log) {
                        $data[] = [
                            'dt' => $log->dt,
                            'message' => $log->message,
                            'type' => $log->type,
                        ];
                    }
                    Logs::insert($data);
                    $count += $chunksize;
                }
                echo 'inserted ' . $count . ' rows ' . memory_get_peak_usage(true) . PHP_EOL;
            }
        }
    }
}
