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
    public function up(): void
{
    Schema::create('borrowers_agreements', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('division_id');
        $table->string('borrower_name');
        $table->string('agreement_number');
        $table->date('start_date');
        $table->date('end_date');
        $table->timestamps();

        $table->foreign('division_id')->references('id')->on('divisions')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('borrowers_agreements');
    }

};
