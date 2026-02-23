<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('remittance_items', function (Blueprint $table) {
            // For check payments
            if (!Schema::hasColumn('remittance_items', 'check_date')) {
                $table->date('check_date')->nullable()->after('account_number');
            }
            if (!Schema::hasColumn('remittance_items', 'remarks')) {
                $table->string('remarks', 255)->nullable()->after('check_date');
            }

            // For cash denominations
            if (!Schema::hasColumn('remittance_items', 'denomination')) {
                $table->decimal('denomination', 12, 2)->nullable()->after('type');
            }
            if (!Schema::hasColumn('remittance_items', 'pcs')) {
                $table->integer('pcs')->nullable()->after('denomination');
            }
        });
    }

    public function down(): void
    {
        Schema::table('remittance_items', function (Blueprint $table) {
            if (Schema::hasColumn('remittance_items', 'pcs')) {
                $table->dropColumn('pcs');
            }
            if (Schema::hasColumn('remittance_items', 'denomination')) {
                $table->dropColumn('denomination');
            }
            if (Schema::hasColumn('remittance_items', 'remarks')) {
                $table->dropColumn('remarks');
            }
            if (Schema::hasColumn('remittance_items', 'check_date')) {
                $table->dropColumn('check_date');
            }
        });
    }
};

