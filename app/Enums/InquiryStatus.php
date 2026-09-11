<?php

namespace App\Enums;

enum InquiryStatus: string
{
    case Pending = 'pending';
    case BacklogCreated = 'backlog_created';
    case Failed = 'failed';
}
