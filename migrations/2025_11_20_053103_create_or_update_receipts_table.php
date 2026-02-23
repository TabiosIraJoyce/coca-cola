<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('receipts')) {
            Schema::create('receipts', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });
        }

        Schema::table('receipts', function (Blueprint $table){
            if (!Schema::hasColumn('receipts', 'route')) $table->string('route')->nullable();
            if (!Schema::hasColumn('receipts', 'leadman')) $table->string('leadman')->nullable();
            if (!Schema::hasColumn('receipts', 'fc')) $table->string('fc')->nullable();
            if (!Schema::hasColumn('receipts', 'hc')) $table->string('hc')->nullable();
            if (!Schema::hasColumn('receipts', 'box')) $table->integer('box')->default(0);
            if (!Schema::hasColumn('receipts', 'total_cases')) $table->integer('total_cases')->default(0);
            if (!Schema::hasColumn('receipts', 'total_ucs')) $table->integer('total_ucs')->default(0);
            if (!Schema::hasColumn('receipts', 'no_of_receipts')) $table->integer('no_of_receipts')->default(0);
            if (!Schema::hasColumn('receipts', 'customer_counts')) $table->integer('customer_counts')->default(0);
            if (!Schema::hasColumn('receipts', 'gross_sales')) $table->decimal('gross_sales', 10, 2)->default(0);
            if (!Schema::hasColumn('receipts', 'sales_discounts')) $table->decimal('sales_discounts', 10, 2)->default(0);
            if (!Schema::hasColumn('receipts', 'coupon_discount')) $table->decimal('coupon_discount', 10, 2)->default(0);
            if (!Schema::hasColumn('receipts', 'net_sales')) $table->decimal('net_sales', 10, 2)->default(0);
            if (!Schema::hasColumn('receipts', 'containers_deposit')) $table->decimal('containers_deposit', 10, 2)->default(0);
            if (!Schema::hasColumn('receipts', 'purchased_refund')) $table->decimal('purchased_refund', 10, 2)->default(0);
            if (!Schema::hasColumn('receipts', 'stock_transfer')) $table->decimal('stock_transfer', 10, 2)->default(0);
            if (!Schema::hasColumn('receipts', 'net_credit_sales')) $table->decimal('net_credit_sales', 10, 2)->default(0);
            if (!Schema::hasColumn('receipts', 'shortage_collections')) $table->decimal('shortage_collections', 10, 2)->default(0);
            if (!Schema::hasColumn('receipts', 'ar_collections')) $table->decimal('ar_collections', 10, 2)->default(0);
            if (!Schema::hasColumn('receipts', 'other_income')) $table->decimal('other_income', 10, 2)->default(0);
            if (!Schema::hasColumn('receipts', 'cash_proceeds')) $table->decimal('cash_proceeds', 10, 2)->default(0);
            if (!Schema::hasColumn('receipts', 'remittance_cash')) $table->decimal('remittance_cash', 10, 2)->default(0);
            if (!Schema::hasColumn('receipts', 'remittance_check')) $table->decimal('remittance_check', 10, 2)->default(0);
            if (!Schema::hasColumn('receipts', 'total_remittance')) $table->decimal('total_remittance', 10, 2)->default(0);
            if (!Schema::hasColumn('receipts', 'shortage_overage')) $table->decimal('shortage_overage', 10, 2)->default(0);

            // ✅ Add Mode of Remittance column
            if (!Schema::hasColumn('receipts', 'mode_of_remittance')) {
                $table->string('mode_of_remittance')->nullable()->comment('cash or cheque');
            }
        });
    }

    public function down()
    {
        Schema::table('receipts', function (Blueprint $table) {
            $table->dropColumn([
                'route','leadman','fc','hc','box','total_cases','total_ucs','no_of_receipts',
                'customer_counts','gross_sales','sales_discounts','coupon_discount','net_sales',
                'containers_deposit','purchased_refund','stock_transfer','net_credit_sales',
                'shortage_collections','ar_collections','other_income','cash_proceeds',
                'remittance_cash','remittance_check','total_remittance','shortage_overage',
                'mode_of_remittance'
            ]);
        });
    }
};
