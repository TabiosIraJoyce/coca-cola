<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('receipts')) {
            return;
        }

        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receipts');

{
    Schema::create('receipts', function (Blueprint $table) {
        $table->id();
        $table->string('route')->nullable();
        $table->string('leadman')->nullable();
        $table->string('fc')->nullable();
        $table->string('hc')->nullable();
        $table->integer('box')->nullable();
        $table->integer('total_cases')->nullable();
        $table->integer('total_ucs')->nullable();
        $table->integer('no_of_receipts')->nullable();
        $table->integer('customer_counts')->nullable();
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
    });
}

    }
};
