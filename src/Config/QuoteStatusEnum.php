<?php

namespace App\Config;

enum QuoteStatusEnum: string
{
    case DRAFT = "draft";
    case SENT = "sent";
    case ACCEPTED = "accepted";
    case REFUSED = "refused";
}