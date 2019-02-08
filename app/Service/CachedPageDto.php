<?php

namespace App\Service;

class CachedPageDto
{
    public $dt;
    public $page;
    public $offset;

    public function __construct(string $dt, ?int $page, int $offset)
    {
        $this->dt = $dt;
        $this->page = $page;
        $this->offset = $offset;
    }
}
