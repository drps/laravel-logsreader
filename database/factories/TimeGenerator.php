<?php

class TimeGenerator
{
    private static $instance;
    private static $seconds = 0;

    private function __construct() {}

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function add(int $seconds)
    {
        self::$seconds += $seconds;
        return self::$seconds;
    }
}
