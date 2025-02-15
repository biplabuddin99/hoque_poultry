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
        Schema::table('temporary_sales', function (Blueprint $table) {
            $table->string('area_id')->nullable()->after('shop_id');
            $table->decimal('receive_amount',10,2)->default(0)->nullable()->after('sales_date');
            $table->integer('sales_type')->comment('0=regular, 1=selected')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temporary_sales', function (Blueprint $table) {
            $table->dropColumn('area_id');
            $table->dropColumn('receive_amount');
            $table->dropColumn('sales_type');
        });
    }
};
