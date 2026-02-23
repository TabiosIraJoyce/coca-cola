<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sales_templates')) {
            return;
        }

        Schema::create('sales_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_line_id')->constrained('business_lines')->cascadeOnDelete();
            $table->string('field_label');
            $table->string('field_type')->default('number');
            $table->boolean('is_required')->default(false);
            $table->unsignedInteger('field_order')->default(0);
            $table->timestamps();

            $table->index(['business_line_id', 'field_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_templates');
    }
};

