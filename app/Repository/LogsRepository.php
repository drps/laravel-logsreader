<?php

declare(strict_types=1);

namespace App\Repository;

use App\Logs;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class LogsRepository
{
    public function countByType(?int $type): int
    {
        $query = DB::table('logs');
        if (!empty($type)) {
            $query->where('type', $type);
        }

        return $query->count();
    }

    public function findLogs(string $sort, ?int $type, ?string $dt, int $offset, int $perPage): Collection
    {
        $query = Logs::orderBy('dt', $sort);

        if ($type) {
            $query->where('type', $type);
        }

        if ($dt) {
            $sign = $sort === 'asc' ? '>' : '<=';
            $query->where('dt', $sign, $dt);
        }

        return $query->limit($perPage)->offset($offset)->get();
    }
}
