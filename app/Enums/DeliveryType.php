<?php

namespace App\Enums;

enum DeliveryType: int
{
    case PICKUP = 1;   // 外送
    case DELIVERY = 2; // 自取
}
