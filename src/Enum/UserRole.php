<?php
namespace App\Enum;

enum UserRole: string
{
    case ADMIN = 'ROLE_ADMIN';
    case MANAGER = 'ROLE_MANAGER';
    case CLIENT = 'ROLE_CLIENT';
}
