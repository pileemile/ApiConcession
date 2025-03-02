<?php

namespace App\Enum;

enum FuelType: string
{
    case Essence = 'essence';
    case Diesel = 'diesel';
    case Electrique = 'électrique';
    case Hybride = 'hybride';
}
