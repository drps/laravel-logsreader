<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    protected $table = 'logs';
    protected $guarded = ['id'];
    protected $casts = ['dt' => 'datetime'];
    public $timestamps = false;

    public static function types(): array
    {
        return [
            1 => 'Type 1',
            2 => 'Type 2',
            3 => 'Type 3',
            4 => 'Type 4',
            5 => 'Type 5',
            6 => 'Type 6',
            7 => 'Type 7',
            8 => 'Type 8',
            9 => 'Type 8',
            10 => 'Type 10',
        ];
    }
}
