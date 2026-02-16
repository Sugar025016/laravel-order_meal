<?php

namespace App\Enums;

enum OrderStatus: int
{
    case PENDING = 1;           // 尚未確認（使用者剛送出）
    case ACCEPTED = 2;          // 店家已接單
    case COOKING = 3;           // 製作中
    case READY_FOR_PICKUP = 4;  // 餐點完成，可取餐
    case ON_THE_WAY = 5;        // 配送中（外送訂單）
    case DELIVERED = 6;         // 已送達
    case CANCELLED = 7;         // 已取消
}
