<?php

namespace Yormy\ProjectMembersLaravel\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public static function formatDate(?Carbon $date): string
    {
        if (!$date) {
            return "";
        }

        return $date->format(config('datetime.date_format'));
    }

    public static function nowUTC() : Carbon
    {
        return Carbon::now('UTC');
    }

    public static function formatDateTime($date)
    {
        if (!$date) {
            return "";
        }

        return $date->format(config('datetime.datetime_format'));
    }

    public static function formatDateTimeFromString(?string $dateString)
    {
        if (empty($dateString)) {
            return "";
        }

        $date = Carbon::parse($dateString);
        return self::formatDateTime($date);
    }
}
