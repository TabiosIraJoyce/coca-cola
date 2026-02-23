<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Division;

class PeriodReport extends Model
{
    protected $table = 'period_reports';
    protected $fillable = [
        'division_id',
        'branch',
        'period_no',
        'report_date',
        'shipment_no',
        'period_name',

        'target_sales',
        'actual_sales',
        'total_variance',
        'achievement_pct',

        'start_date',
        'end_date',
        'date_from',
        'date_to',

        'status',
        'submitted_at',
        'approved_at',
        'approved_by',

        'collections',
        'cash_proceeds',

        'inventory_rows',
        'custom_tables',
        'coke_rows',

        'core_target_sales',
        'petcsd_target_sales',
        'stills_target_sales',

        'core_achievement_pct',
        'petcsd_achievement_pct',
        'stills_achievement_pct',

        'core_actual_sales',
        'petcsd_actual_sales',
        'stills_actual_sales',

        'core_variance',
        'petcsd_variance',
        'stills_variance',

        'is_target_locked',
    ];

    protected $casts = [
        'report_date' => 'date',
        'start_date'  => 'date',
        'end_date'    => 'date',
        'date_from'   => 'date',
        'date_to'     => 'date',
        'inventory_rows' => 'array',
        'custom_tables'  => 'array',
        'coke_rows'      => 'array',
        'submitted_at'   => 'datetime',
        'approved_at'    => 'datetime',
    ];

    /* ===========================
       RELATIONSHIPS
    ============================ */

    // ✅ CORE + IWS ITEMS
    public function items()
    {
        return $this->hasMany(PeriodReportItem::class);
    }

    // ✅ INVENTORY (🔥 THIS WAS MISSING)
    public function inventories()
    {
        return $this->hasMany(PeriodReportInventory::class);
    }

    // ✅ CUSTOM TABLES (for additional tables)
    public function customTables()
    {
        return $this->hasMany(PeriodReportCustomTable::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    /* ===========================
       COMPUTED ACCESSORS (🔥 FIX)
    ============================ */

    /**
     * 🔢 Actual Sales (always computed from items)
     */
    public function getActualSalesCalcAttribute()
    {
        return (float) $this->items()
            ->sum(DB::raw('core_total_ucs + iws_total_ucs'));
    }

    /**
     * 📈 Achievement Percentage
     */
    public function getAchievementPctCalcAttribute()
    {
        if (($this->target_sales ?? 0) <= 0) {
            return 0;
        }

        return round(
            ($this->actual_sales_calc / $this->target_sales) * 100,
            2
        );
    }

    /**
     * 📉 Variance (Target − Actual)
     */
    public function getVarianceCalcAttribute()
    {
        return round(
            ($this->target_sales ?? 0) - $this->actual_sales_calc,
            2
        );
    }

    public function dailySales()
    {
        return $this->hasMany(
            \App\Models\DailyCokeSales::class,
            'period_no',
            'period_no'
        )->whereColumn('branch', 'period_reports.branch');
    }
}
