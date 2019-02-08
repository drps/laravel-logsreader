<?php

use App\Logs;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LogsSeeder extends Seeder
{
    public function run()
    {
        $logs = DB::select('select max(dt) maxdt FROM logs');
        $startDatetime = Carbon::createFromFormat('Y-m-d H:i:s', $logs[0]->maxdt ?: date('Y-m-d H:i:s'));

        $seconds = 0;
        $count = 0;
        for ($j = 0; $j < 10; $j++) {
            for ($i = 0; $i < 10; $i++) {
                $logs = factory(Logs::class, 20000)->make([$startDatetime])->all();
                $chunksize = 100;

                foreach (array_chunk($logs, $chunksize) as $chunk) {
                    $data = array();
                    foreach ($chunk as $log) {
                        $seconds += random_int(1,4);
                        $data[] = [
                            'dt' => $log->dt->addSeconds($seconds),
                            'message' => $log->message,
                            'type' => $log->type,
                        ];
                    }
                    Logs::insert($data);
                    $count += $chunksize;
                }
                echo 'inserted ' . $count . ' rows; ' . 'Last date ' . $data[count($data) - 1]['dt']->format('Y-m-d H:i:s') . '; Memory peak:' . memory_get_peak_usage(true) . PHP_EOL;
            }
        }
    }
}
