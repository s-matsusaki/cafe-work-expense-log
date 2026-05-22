<?php

namespace App\Models\Concerns;

use Carbon\CarbonInterface;

trait FormatsDateWithWeekday
{
    protected function formatDateWithWeekday(?CarbonInterface $date): ?string
    {
        if (is_null($date)) {
            return null;
        }

        $weekdays = ['日', '月', '火', '水', '木', '金', '土'];

        return $date->format('Y-m-d').'（'.$weekdays[$date->dayOfWeek].'）';
    }
}
