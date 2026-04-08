<?php

namespace App\Enums;

enum OrderType: int
{
    case IMMEDIATE = 1; // 即時訂單
    case SCHEDULED = 2; // 預訂單
}
