<?php

namespace App\Enums;

enum Permission: string
{
    case SELL = 'sell';
    case CREATE_PRODUCT = 'create_product';
    case CREATE_LOCATION = 'create_location';
    case EDIT_LOCATION = 'edit_location';
    case CREATE_RETURN = 'create_return';
}
