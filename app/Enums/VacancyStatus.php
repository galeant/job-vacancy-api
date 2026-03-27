<?php

namespace App\Http\Enums;

enum VacancyStatus: int
{
    case DRAFT = 1;
    case PUBLISHED = 2;
    case INACTIVE = 99;
}
