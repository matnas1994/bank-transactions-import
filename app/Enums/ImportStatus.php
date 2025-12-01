<?php

namespace App\Enums;

enum ImportStatus: string
{
    case SUCCESS = 'success';
    case PARTIAL = 'partial';
    case FAILED = 'failed';
}
