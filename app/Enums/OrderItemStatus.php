<?php

namespace App\Enums;

enum OrderItemStatus: int
{
    case NORMAL = 1;
    case PARTIAL_OUT_OF_STOCK = 2;
    case OUT_OF_STOCK = 3;
    case CANCELLED = 4;
}
