<?php

namespace App\Enums;

enum ProductType: string
{
    case MONTHLY = 'monthly';
    case YEARLY = 'yearly';
    case ONETIME = 'one_time';
}
