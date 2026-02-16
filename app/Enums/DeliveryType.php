<?php

namespace App\Enums;

enum DeliveryType: int
{
    case PICKUP = 1;   // 自取
    case DELIVERY = 2; // 外送
}
