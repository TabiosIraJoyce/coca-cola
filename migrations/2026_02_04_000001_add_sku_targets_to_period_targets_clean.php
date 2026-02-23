<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('period_targets_clean')) {
            return;
        }

        if (!Schema::hasColumn('period_targets_clean', 'sku_targets')) {
            Schema::table('period_targets_clean', function (Blueprint $table) {
                // Store as JSON text so we can keep the per-SKU breakdown without requiring a new table.
                $table->longText('sku_targets')->nullable()->after('stills_target_sales');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('period_targets_clean') && Schema::hasColumn('period_targets_clean', 'sku_targets')) {
            Schema::table('period_targets_clean', function (Blueprint $table) {
                $table->dropColumn('sku_targets');
            });
        }
    }
};

