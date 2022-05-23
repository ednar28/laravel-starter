<?php

namespace App\Enums;

enum Roles: string
{
    use HelperEnum;

    case SUPERADMIN = 'superadmin';
}
