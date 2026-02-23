<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('receipt_items', function (Blueprint $table) {
            // These fields are used by the Receipts input table + consolidated print preview.
            if (!Schema::hasColumn('receipt_items', 'total_ucs')) {
                $table->decimal('total_ucs', 15, 2)->default(0)->after('total_cases');
            }
            if (!Schema::hasColumn('receipt_items', 'number_of_receipts')) {
                $table->integer('number_of_receipts')->default(0)->after('total_ucs');
            }
            if (!Schema::hasColumn('receipt_items', 'customer_count')) {
                $table->integer('customer_count')->default(0)->after('number_of_receipts');
            }
        });
    }

    public function down(): void
    {
        Schema::table('receipt_items', function (Blueprint $table) {
            if (Schema::hasColumn('receipt_items', 'customer_count')) {
                $table->dropColumn('customer_count');
            }
            if (Schema::hasColumn('receipt_items', 'number_of_receipts')) {
                $table->dropColumn('number_of_receipts');
            }
            if (Schema::hasColumn('receipt_items', 'total_ucs')) {
                $table->dropColumn('total_ucs');
            }
        });
    }
};

