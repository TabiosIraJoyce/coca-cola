<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('remittance')) {
            return;
        }

        Schema::create('remittance', function (Blueprint $table) {
            $table->id();

            // Required fields
            $table->unsignedBigInteger('division_id')->nullable();
            $table->date('date')->nullable();

            // CHECK PAYMENT
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->date('check_date')->nullable();
            $table->string('check_remarks')->nullable();
            $table->decimal('check_amount', 12, 2)->nullable();

            // CASH DETAILS
            $table->string('cash_denomination')->nullable();
            $table->integer('cash_pcs')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remittance');
    }
};
