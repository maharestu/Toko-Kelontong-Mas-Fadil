<?php

namespace App\Enums;

enum MovementType: string
{
    case Restock = 'restock';
    case Sale = 'sale';
    case Adjustment = 'adjustment';
}
