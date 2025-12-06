<?php

namespace App\Enum;

enum Status: string
{
    case FINISHED = 'FINISHED';
    case TO_DO = 'TO DO';
    case ON_GOING = 'ON GOING';
    case PENDING = 'PENDING';
}
