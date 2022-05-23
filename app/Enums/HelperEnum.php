<?php

namespace App\Enums;

trait HelperEnum
{
    public static function values()
    {
        return array_column(self::cases(), 'value');
    }
}
