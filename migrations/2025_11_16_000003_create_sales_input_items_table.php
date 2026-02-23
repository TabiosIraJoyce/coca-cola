<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sales_input_items')) {
            return;
        }

        Schema::create('sales_input_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_input_id')->constrained('sales_inputs')->cascadeOnDelete();
            $table->string('field_label');
            $table->string('field_type')->nullable();
            $table->text('value')->nullable();
            $table->timestamps();

            $table->index('field_label');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_input_items');
    }
};

