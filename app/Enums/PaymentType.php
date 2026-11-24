<?php

namespace App\Enums;

enum PaymentType: string
{
    case Langsung = 'langsung';
    case Kredit = 'kredit';
}
