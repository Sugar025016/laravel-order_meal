<?php

namespace App\Enums;

enum PayMethod: int
{
    case CASH = 1;
    case CREDIT_CARD = 2;
    case LINE_PAY = 3;
}
