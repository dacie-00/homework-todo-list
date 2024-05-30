<?php

namespace App;

class IDGenerator
{
    static array $ids = [];
    static function id()
    {
        $newID = end(self::$ids) + 1;
        self::$ids[] = $newID;
        return $newID;
    }

    static function populate($ids)
    {
        self::$ids = $ids;
    }

}