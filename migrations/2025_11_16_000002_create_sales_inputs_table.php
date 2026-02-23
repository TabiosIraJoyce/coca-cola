<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sales_inputs')) {
            return;
        }

        Schema::create('sales_inputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->nullable()->constrained('divisions')->nullOnDelete();
            $table->foreignId('business_line_id')->nullable()->constrained('business_lines')->nullOnDelete();
            $table->date('date')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();

            $table->index('date');
            $table->index(['division_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_inputs');
    }
};

