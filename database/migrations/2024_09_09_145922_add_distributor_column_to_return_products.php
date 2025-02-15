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
        Schema::table('return_products', function (Blueprint $table) {
            $table->integer('distributor_id')->nullable()->after('id');
            $table->date('return_date')->nullable()->after('distributor_id');
            $table->integer('return_type')->nullable()->after('return_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('return_products', function (Blueprint $table) {
            $table->dropColumn('distributor_id');
            $table->dropColumn('return_date');
            $table->dropColumn('return_type');
        });
    }
};
