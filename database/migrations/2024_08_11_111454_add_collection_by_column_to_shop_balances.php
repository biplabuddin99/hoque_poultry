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
    public function up()
    {
        Schema::table('shop_balances', function (Blueprint $table) {
            $table->string('collection_by')->nullable()->after('sr_id');
            $table->decimal('collect_amount',10,2)->default(0)->nullable()->after('collection_by');
            $table->decimal('check_collect_amount',10,2)->default(0)->nullable()->after('collect_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_balances', function (Blueprint $table) {
            $table->dropColumn('collection_by');
            $table->dropColumn('collect_amount');
            $table->dropColumn('check_collect_amount');
        });
    }
};
