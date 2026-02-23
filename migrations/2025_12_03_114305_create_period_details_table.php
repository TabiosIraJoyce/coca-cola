<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('period_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_report_id')->constrained()->onDelete('cascade');

            $table->json('columns')->nullable(); // dynamic headers
            $table->json('rows')->nullable();    // dynamic rows

            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('period_details');
    }
};
