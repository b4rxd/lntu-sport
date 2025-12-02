<?php

namespace App\Enums;

enum CardStatus: string
{
    case AVAILABLE = 'available';
    case ACTIVE = 'active';
    case LOST = 'lost';
    case DAMAGED = 'damaged';
}
