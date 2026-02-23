<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('period_reports')) {
            return;
        }

        if (!Schema::hasColumn('period_reports', 'coke_rows')) {
            Schema::table('period_reports', function (Blueprint $table) {
                $table->longText('coke_rows')->nullable()->after('custom_tables');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('period_reports')) {
            return;
        }

        if (Schema::hasColumn('period_reports', 'coke_rows')) {
            Schema::table('period_reports', function (Blueprint $table) {
                $table->dropColumn('coke_rows');
            });
        }
    }
};
