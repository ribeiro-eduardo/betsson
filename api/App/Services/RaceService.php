<?php

namespace App\Services;

class RaceService
{
    private static $path = __DIR__. '/../next_races.json';
    
    public static function getNextRaces()
    {
        $json = file_get_contents(self::$path);

        return json_decode($json)->data->races;
    }
}