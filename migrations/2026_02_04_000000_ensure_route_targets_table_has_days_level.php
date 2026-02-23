<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('route_targets')) {
            Schema::create('route_targets', function (Blueprint $table) {
                $table->id();
                $table->string('route');
                $table->string('leadman');
                $table->unsignedTinyInteger('month');
                $table->unsignedSmallInteger('year');
                $table->decimal('target_sales', 12, 2)->default(0);
                $table->unsignedInteger('days_level')->nullable();
                $table->timestamps();

                $table->unique(['route', 'leadman', 'month', 'year'], 'route_targets_unique');
            });

            return;
        }

        if (!Schema::hasColumn('route_targets', 'days_level')) {
            Schema::table('route_targets', function (Blueprint $table) {
                $table->unsignedInteger('days_level')->nullable()->after('target_sales');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('route_targets') && Schema::hasColumn('route_targets', 'days_level')) {
            Schema::table('route_targets', function (Blueprint $table) {
                $table->dropColumn('days_level');
            });
        }
    }
};
