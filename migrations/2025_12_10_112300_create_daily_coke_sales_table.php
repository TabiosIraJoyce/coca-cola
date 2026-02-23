<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('daily_coke_sales')) {
            return;
        }

        Schema::create('daily_coke_sales', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('period_id');
            $table->string('branch');
            $table->date('date');

            $table->string('size');
            $table->string('product');

            $table->decimal('core_cases', 10, 2)->default(0);
            $table->decimal('core_ucs', 10, 6)->default(0);
            $table->decimal('iws_cases', 10, 2)->default(0);
            $table->decimal('iws_ucs', 10, 6)->default(0);
            $table->decimal('total_ucs', 12, 2)->default(0);

            $table->timestamps();

            // Foreign key but optional (remove if errors)
            $table->foreign('period_id')->references('id')->on('period_reports')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_coke_sales');
    }
};
