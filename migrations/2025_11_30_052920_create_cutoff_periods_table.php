<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cutoff_periods')) {
            return;
        }

        Schema::create('cutoff_periods', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('division_id')->nullable();
    $table->integer('period_number');
    $table->date('start_date');
    $table->date('end_date');
    $table->integer('rt_days');
    $table->timestamps();
    $table->decimal('target_sales', 15, 2)->nullable();
    $table->decimal('actual_sales', 15, 2)->nullable();
});


    }

    public function down(): void
    {
        Schema::dropIfExists('cutoff_periods');
    }
};
