<?php

namespace App\Enums;

enum MatingTypeEnum: string
{
    case NATURAL = 'natural';
    case ARTIFICIAL_INSEMINATION = 'artificial_insemination';
    case EMBRIO_TRANSPLANTATION = 'embrio_transplantation';
}
