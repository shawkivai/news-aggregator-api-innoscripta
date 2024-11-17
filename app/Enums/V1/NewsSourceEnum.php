<?php

namespace App\Enums\V1;

enum NewsSourceEnum: string
{
    case NEWSAPI = 'newsapi';
    case THE_GUARDIAN = 'the guardian';
    case NEW_YORK_TIMES = 'new york times';
}
