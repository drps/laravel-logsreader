<?php

namespace App\Console\Commands;

use App\Logs;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CachePaginator extends Command
{
    protected $signature = 'paginator:cache';

    protected $description = 'Caches pairs datetimes => page';

    private $perPage;

    public function __construct(int $perPage)
    {
        parent::__construct();
        $this->perPage = $perPage;
    }

    public function handle()
    {
        Cache::flush();
        $types = array_keys(Logs::types());
        $types[] = 0;
        foreach ($types as $type) {
            $data = $this->indexByType((int)$type);
            Cache::forever('type_' . (int)$type, $data);
        }

        return true;
    }

    private function indexByType(int $type)
    {
        $chunkSize = 100000;
        $prevDt = '2000-01-01 00:00:00';

        if ($type) {
            $sql = "select t.*, count(*) over () cnt from (select * from logs where dt > ? and type = {$type} order by dt limit ?) t order by dt desc limit  1";
        } else {
            $sql = 'select t.*, count(*) over () cnt from (select * from logs where dt > ? order by dt limit ? ) t order by dt desc limit  1';
        }

        $data = [];
        $page = 0;

        do {
            if ($lastLogInChunk = DB::select($sql, [$prevDt, $chunkSize])) {
                $page += ($chunkSize / $this->perPage);

                // if rows count >= perPage
                if ($lastLogInChunk[0]->cnt >= $chunkSize) {
                    $data[$page] = $lastLogInChunk[0]->dt;
                }

                $prevDt = $lastLogInChunk[0]->dt;
            }

        } while ($lastLogInChunk);

        return $data;
    }
}
