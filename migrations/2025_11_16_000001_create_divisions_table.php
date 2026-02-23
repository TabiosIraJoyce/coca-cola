<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('divisions')) {
            return;
        }

        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_line_id')->nullable()->constrained('business_lines')->nullOnDelete();
            $table->string('division_name');
            $table->string('supervisor_name')->nullable();
            $table->string('oic_name')->nullable();
            $table->string('division_address')->nullable();
            $table->string('division_contact_number')->nullable();
            $table->string('division_telephone_number')->nullable();
            $table->timestamps();

            $table->index('division_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('divisions');
    }
};

