<?php

namespace App\Enums;

enum PositionEnum: string
{
    case DEAN              = 'dean';
    case DEPARTMENT_HEAD   = 'department_head';
    case LECTURER          = 'lecturer';
    case STUDENT           = 'student';
}
