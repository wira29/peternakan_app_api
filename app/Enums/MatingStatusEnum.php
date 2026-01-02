<?php

namespace App\Enums;

enum MatingStatusEnum: string
{
    case NOTSUCCES = 'not_success';
    case PREGNANT = 'pregnant'; 
    case MISCARRIAGE = 'miscarriage';
}