<?php

namespace App\Helpers;

/**
 * Class SessionHelper
 * Provides utility methods related to sessions, such as getting the current day's letter and session colors.
 */
class SessionHelper
{
    /**
     * Gets the letter for the current day according to the day of the week.
     *
     * @return string|null
     */
    public static function getNowLetter(): ?string
    {
        return match (now()->dayOfWeek) {
            1 => 'L', 2 => 'M', 3 => 'X', 4 => 'J', 5 => 'V',
            default => null,
        };
    }

    /**
     * Returns an array of colors associated with each session.
     *
     * @return array
     */
    public static function getSessionColors(): array
    {
        return [
            1 => '#752948',
            2 => '#66d95b',
            3 => '#7340db',
            4 => '#a05195',
            5 => '#d42a71',
            6 => '#f52a3b',
            7 => '#f59064',
            8 => '#00fffb',
        ];
    }
}
