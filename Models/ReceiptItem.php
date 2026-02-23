<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReceiptItem extends Model
{
    use HasFactory;

    protected $table = 'receipt_items';

    /**
     * Allow mass assignment for ALL columns.
     * This fixes the issue where gross_sales and other
     * numeric fields were being saved as 0.
     */
    protected $guarded = [];

    protected $casts = [
        'gross_sales'          => 'decimal:2',
        'sales_discounts'      => 'decimal:2',
        'coupon_discount'      => 'decimal:2',
        'net_sales'            => 'decimal:2',
        'total_ucs'            => 'decimal:2',
        'containers_deposit'   => 'decimal:2',
        'purchased_refund'     => 'decimal:2',
        'stock_transfer'       => 'decimal:2',
        'net_credit_sales'     => 'decimal:2',
        'shortage_collections' => 'decimal:2',
        'ar_collections'       => 'decimal:2',
        'other_income'         => 'decimal:2',
        'cash_proceeds'        => 'decimal:2',
        'remittance_cash'      => 'decimal:2',
        'remittance_check'     => 'decimal:2',
        'total_remittance'     => 'decimal:2',
        'shortage_overage'     => 'decimal:2',
    ];

    /* =========================
       RELATIONSHIP
    ========================= */
    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }
}
