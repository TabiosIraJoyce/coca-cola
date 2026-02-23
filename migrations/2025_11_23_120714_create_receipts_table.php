<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('receipts')) {
            return;
        }

        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('division_id');
            $table->date('report_date')->nullable();
            $table->string('route')->nullable();
            $table->string('leadman')->nullable();
            $table->string('fc')->nullable();
            $table->string('hc')->nullable();
            $table->decimal('box', 10, 2)->nullable();
            $table->decimal('total_cases', 10, 2)->nullable();
            $table->decimal('total_ucs', 10, 2)->nullable();
            $table->decimal('no_of_receipts', 10, 2)->nullable();
            $table->decimal('customer_counts', 10, 2)->nullable();

            // Money fields
            $table->decimal('gross_sales', 12, 2)->nullable();
            $table->decimal('sales_discounts', 12, 2)->nullable();
            $table->decimal('coupon_discount', 12, 2)->nullable();
            $table->decimal('net_sales', 12, 2)->nullable();
            $table->decimal('containers_deposit', 12, 2)->nullable();
            $table->decimal('purchased_refund', 12, 2)->nullable();
            $table->decimal('stock_transfer', 12, 2)->nullable();
            $table->decimal('net_credit_sales', 12, 2)->nullable();
            $table->decimal('shortage_collections', 12, 2)->nullable();
            $table->decimal('ar_collections', 12, 2)->nullable();
            $table->decimal('other_income', 12, 2)->nullable();
            $table->decimal('cash_proceeds', 12, 2)->nullable();
            $table->decimal('remittance_cash', 12, 2)->nullable();
            $table->decimal('remittance_check', 12, 2)->nullable();
            $table->decimal('total_remittance', 12, 2)->nullable();
            $table->decimal('shortage_overage', 12, 2)->nullable();

            $table->timestamps();

            $table->foreign('division_id')->references('id')->on('divisions')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
