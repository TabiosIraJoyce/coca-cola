<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // The table may already exist in some deployments (manual setup / legacy DB).
        if (Schema::hasTable('period_reports')) {
            return;
        }

        Schema::create('period_reports', function (Blueprint $table) {
            $table->id();
            $table->string('branch');                    // Solsona, Laoag, Batac
            $table->unsignedTinyInteger('period_no');    // 1–12
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            // Summary fields
            $table->decimal('target_sales', 15, 2)->default(0);        // target
            $table->decimal('actual_sales', 15, 2)->default(0);       // actual deliveries / sales
            $table->decimal('total_remitted', 15, 2)->default(0);     // total remitted
            $table->decimal('total_variance', 15, 2)->default(0);     // auto: actual - remitted
            $table->decimal('achievement_pct', 8, 2)->default(0);     // auto: actual / target * 100
            $table->decimal('ending_receivables', 15, 2)->default(0);
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();


            // Fully flexible table, stored as JSON
            $table->longText('table_json')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('period_reports');
    }
};
