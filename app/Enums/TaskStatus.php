<?php

namespace App\Enums;

/**
 * Enum representing task status.
 */
enum TaskStatus: string
{
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Canceled = 'canceled';

    /**
     * Get all status values.
     *
     * @return array The array of status values.
     */
    public static function values(): array
    {
        return [
            self::InProgress,
            self::Completed,
            self::Canceled,
        ];
    }
}
