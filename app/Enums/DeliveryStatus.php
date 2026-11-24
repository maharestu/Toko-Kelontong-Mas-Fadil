<?php

namespace App\Enums;

enum DeliveryStatus: string
{
    case Menunggu = 'menunggu';
    case Diantar = 'diantar';
    case Selesai = 'selesai';
    case Dibatalkan = 'dibatalkan';
}
