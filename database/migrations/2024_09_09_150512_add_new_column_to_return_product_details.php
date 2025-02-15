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
        Schema::table('return_product_details', function (Blueprint $table) {
            $table->integer('return_type')->nullable()->after('return_product_id');
            $table->integer('group_id')->nullable()->after('return_type');
            $table->integer('ctn_return')->nullable()->after('product_id');
            $table->integer('pcs_return')->nullable()->after('ctn_return');
            $table->integer('total_pcs_return')->nullable()->after('pcs_return');
            $table->integer('total_receive_qty')->nullable()->after('total_pcs_return');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('return_product_details', function (Blueprint $table) {
            $table->dropColumn('return_type');
            $table->dropColumn('group_id');
            $table->dropColumn('ctn_return');
            $table->dropColumn('pcs_return');
            $table->dropColumn('total_pcs_return');
            $table->dropColumn('total_receive_qty');
        });
    }
};
