<?php

namespace App\Helpers;

use App\Models\CutoffPeriod;
use Carbon\Carbon;

class Cutoff
{
    public static function current()
    {
        $today = Carbon::today();

        return CutoffPeriod::where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->first();
    }

    public static function daysLeft()
    {
        $p = self::current();
        if (!$p) return null;

        return Carbon::today()->diffInDays(Carbon::parse($p->end_date)) + 1;
    }
}
