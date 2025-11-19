<?php

namespace App\Helpers;

class ReportHelper
{
    public static function makeDirectory($folder)
    {
        $path = storage_path("app/reports/$folder");

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        return $path;
    }
}
