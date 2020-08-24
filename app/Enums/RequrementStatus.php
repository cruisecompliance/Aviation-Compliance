<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static UPCOMING()
 * @method static static IN_PROGRESS()
 * @method static static COMPLETED()
 */
final class RequrementStatus extends Enum
{
    const UPCOMING = 'upcoming';
    const IN_PROGRESS = 'in_progress';
    const COMPLETED = 'completed';
}
