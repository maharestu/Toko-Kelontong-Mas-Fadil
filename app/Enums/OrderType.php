<?php

namespace App\Enums;

enum OrderType: string
{
    case Direct = 'direct';
    case Delivery = 'delivery';
}
