<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // artisan make:migration create_receivables_table
public function up()
{
    if (Schema::hasTable('receivables')) {
        return;
    }

    Schema::create('receivables', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('division_id')->nullable()->index();
        $table->string('si_number')->nullable();
        $table->string('customer_name')->nullable();
        $table->decimal('amount', 12, 2)->nullable();

        $table->string('cr_number')->nullable();
        $table->string('collection_customer')->nullable();
        $table->text('collection_remarks')->nullable();
        $table->string('collection_check_details')->nullable();
        $table->decimal('collection_amount', 12, 2)->nullable();

        $table->string('stock_si')->nullable();
        $table->string('stock_customer')->nullable();
        $table->decimal('stock_amount', 12, 2)->nullable();
        $table->string('stock_yesno')->nullable();

        $table->string('shortage_cr')->nullable();
        $table->string('shortage_customer')->nullable();
        $table->date('shortage_date')->nullable();
        $table->decimal('shortage_amount', 12, 2)->nullable();

        $table->timestamps();

        $table->foreign('division_id')->references('id')->on('divisions')->onDelete('cascade');
    });
}
};
