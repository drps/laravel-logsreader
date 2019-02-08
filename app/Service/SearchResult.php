<?php

namespace App\Service;

use Illuminate\Contracts\Pagination\Paginator;

class SearchResult
{
    public $logs;
    public $sort;
    public $type;

    public function __construct(Paginator $logs, string $sort, ?string $type)
    {
        $this->logs = $logs;
        $this->sort = $sort;
        $this->type = $type;
    }
}
