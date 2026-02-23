<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('products')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'unit_ml')) {
                $table->decimal('unit_ml', 10, 2)->nullable()->after('pack_size');
            }

            if (!Schema::hasColumn('products', 'bottles_per_case')) {
                $table->unsignedInteger('bottles_per_case')->nullable()->after('unit_ml');
            }

            if (!Schema::hasColumn('products', 'ucs')) {
                // UCS factor used in Coke reporting: (unit_ml * bottles_per_case) / 5678
                $table->decimal('ucs', 12, 6)->nullable()->after('bottles_per_case');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('products')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'ucs')) {
                $table->dropColumn('ucs');
            }
            if (Schema::hasColumn('products', 'bottles_per_case')) {
                $table->dropColumn('bottles_per_case');
            }
            if (Schema::hasColumn('products', 'unit_ml')) {
                $table->dropColumn('unit_ml');
            }
        });
    }
};

