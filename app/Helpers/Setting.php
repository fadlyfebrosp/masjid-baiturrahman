<?php

namespace App\Helpers;

class Setting
{
    public static function path()
    {
        return storage_path('app/settings.json');
    }

    public static function load()
    {
        $file = self::path();

        if (!file_exists($file)) {
            return [];
        }

        return json_decode(file_get_contents($file), true);
    }

    public static function save(array $data)
    {
        $file = self::path();
        file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
    }
}
