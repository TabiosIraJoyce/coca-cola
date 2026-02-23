<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateValidatedRemittanceReceiptsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('validated_remittance_receipts')) {
            return;
        }

        Schema::create('validated_remittance_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('validated_remittance_id')->constrained()->onDelete('cascade');
            $table->string('file_path');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('validated_remittance_receipts');
    }

};
