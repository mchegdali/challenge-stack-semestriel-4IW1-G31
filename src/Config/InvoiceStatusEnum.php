<?php

namespace App\Config;

enum InvoiceStatusEnum: string
{
    case CREATED = "created";
    case PAID = "paid";
    case LATE = "late";
    case CANCELLED = "cancelled";
    case REFUND = "refund";
}