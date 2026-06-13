<?php

namespace App\Enums;

enum SubmissionStatusEnum: string
{
    case SUBMITTED = 'submitted';
    case LATE      = 'late';
}
