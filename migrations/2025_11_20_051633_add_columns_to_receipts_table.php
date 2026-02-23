<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('receipts')) {
            return;
        }

        // Prevent duplicate-column errors on databases where the receipts table already exists.
        if (Schema::hasColumn('receipts', 'route')) {
            return;
        }

        Schema::table('receipts', function (Blueprint $table) {
            $table->string('route')->nullable();
            $table->string('leadman')->nullable();
            $table->string('fc')->nullable();
            $table->string('hc')->nullable();
            $table->integer('box')->default(0);
            $table->integer('total_cases')->default(0);
            $table->integer('total_ucs')->default(0);
            $table->integer('no_of_receipts')->default(0);
            $table->integer('customer_counts')->default(0);
            $table->decimal('gross_sales', 10, 2)->default(0);
            $table->decimal('sales_discounts', 10, 2)->default(0);
            $table->decimal('coupon_discount', 10, 2)->default(0);
            $table->decimal('net_sales', 10, 2)->default(0);
            $table->decimal('containers_deposit', 10, 2)->default(0);
            $table->decimal('purchased_refund', 10, 2)->default(0);
            $table->decimal('stock_transfer', 10, 2)->default(0);
            $table->decimal('net_credit_sales', 10, 2)->default(0);
            $table->decimal('shortage_collections', 10, 2)->default(0);
            $table->decimal('ar_collections', 10, 2)->default(0);
            $table->decimal('other_income', 10, 2)->default(0);
            $table->decimal('cash_proceeds', 10, 2)->default(0);
            $table->decimal('remittance_cash', 10, 2)->default(0);
            $table->decimal('remittance_check', 10, 2)->default(0);
            $table->decimal('total_remittance', 10, 2)->default(0);
            $table->decimal('shortage_overage', 10, 2)->default(0);
        });
    }

    public function down()
    {
        Schema::table('receipts', function (Blueprint $table) {
            $table->dropColumn([
                'route', 'leadman', 'fc', 'hc', 'box', 'total_cases', 'total_ucs', 'no_of_receipts',
                'customer_counts', 'gross_sales', 'sales_discounts', 'coupon_discount', 'net_sales',
                'containers_deposit', 'purchased_refund', 'stock_transfer', 'net_credit_sales',
                'shortage_collections', 'ar_collections', 'other_income', 'cash_proceeds',
                'remittance_cash', 'remittance_check', 'total_remittance', 'shortage_overage'
            ]);
        });
    }
};
