<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (Schema::hasColumn('receipt_items', 'item_type')) {
            return;
        }

        Schema::table('receipt_items', function (Blueprint $table) {
            $table->string('item_type')->after('receipt_id');
        });
    }

    public function down()
    {
        if (!Schema::hasColumn('receipt_items', 'item_type')) {
            return;
        }

        Schema::table('receipt_items', function (Blueprint $table) {
            $table->dropColumn('item_type');
        });
    }
};
