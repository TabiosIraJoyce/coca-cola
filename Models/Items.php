public function items()
{
    return $this->hasMany(PeriodReportItem::class);
}

public function computeMetrics()
{
    $actual = $this->items->sum(fn ($i) =>
        ($i->core_total_ucs ?? 0) + ($i->iws_total_ucs ?? 0)
    );

    $target = $this->target_sales ?? 0;

    return [
        'actual'       => $actual,
        'variance'     => $actual - $target,
        'achievement'  => $target > 0
            ? round(($actual / $target) * 100, 2)
            : 0,
    ];
}
