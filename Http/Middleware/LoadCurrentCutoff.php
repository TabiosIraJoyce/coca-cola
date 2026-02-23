<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Cutoff;

class LoadCurrentCutoff
{
    public function handle($request, Closure $next)
    {
        $current = Cutoff::current();
        $daysLeft = Cutoff::daysLeft();

        view()->share('currentCutoff', $current);
        view()->share('cutoffDaysLeft', $daysLeft);

        return $next($request);
    }
}
