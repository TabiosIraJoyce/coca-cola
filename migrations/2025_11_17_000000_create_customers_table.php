<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('customers')) {
            return;
        }

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('delivery_route')->nullable();
            $table->string('sub_route')->nullable();
            $table->string('customer');
            $table->string('store_name');
            $table->string('contact_number')->nullable();
            $table->string('address')->nullable();
            $table->decimal('credit_limit', 15, 2)->default(0);
            $table->string('remarks')->default('ACTIVE');
            $table->timestamps();

            $table->index(['delivery_route', 'sub_route']);
            $table->index('customer');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

