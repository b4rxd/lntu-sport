<?php

namespace App\Enums;

enum VisitLogType: string
{
    case ENTRY = 'entry';
    case EXIT = 'exit';
}