<?php

namespace App\Services;

class ErrorLogService
{
    private static $path = __DIR__ . '/error.log';
    
    public static function log(string $message)
    {
        file_put_contents(self::$path, $message.PHP_EOL, FILE_APPEND);
    }
}