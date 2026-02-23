<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('borrowers')) {
            return;
        }

        Schema::create('borrowers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('division_id');
            $table->date('report_date')->nullable();
            $table->string('borrower_name');
            $table->string('agreement_number');
            $table->json('borrowers_json')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->timestamps();

            $table->foreign('division_id')
                  ->references('id')
                  ->on('divisions')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowers');
    }
};
