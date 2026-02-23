<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('period_reports')) {
            return;
        }

        if (!Schema::hasColumn('period_reports', 'shipment_no')) {
            Schema::table('period_reports', function (Blueprint $table) {
                $table->string('shipment_no')->nullable()->after('report_date');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('period_reports')) {
            return;
        }

        if (Schema::hasColumn('period_reports', 'shipment_no')) {
            Schema::table('period_reports', function (Blueprint $table) {
                $table->dropColumn('shipment_no');
            });
        }
    }
};

