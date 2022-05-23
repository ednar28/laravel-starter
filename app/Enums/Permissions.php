<?php

namespace App\Enums;

enum Permissions: string
{
    use HelperEnum;

    case MANAGE_ADMINS = 'manage admins';
    case MANAGE_USERS = 'manage users';
}
