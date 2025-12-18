<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class EnumHelper
{
    public static function getEnumValues($table, $column)
    {
        // Query harus STRING, bukan DB::raw()
        $result = DB::select("SHOW COLUMNS FROM `$table` WHERE Field = ?", [$column]);

        if (!isset($result[0])) {
            return [];
        }

        $type = $result[0]->Type;

        preg_match('/enum\((.*)\)/', $type, $matches);

        $values = [];

        if (isset($matches[1])) {
            $raw = explode(',', $matches[1]);
            foreach ($raw as $value) {
                $values[] = trim(str_replace("'", "", $value));
            }
        }

        return $values;
    }
}
