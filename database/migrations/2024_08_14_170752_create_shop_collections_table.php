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
        Schema::create('shop_collections', function (Blueprint $table) {
            $table->id();
            $table->string('shop_id')->nullable();
            $table->string('shop_balance_id')->nullable();
            $table->string('sales_id')->nullable();
            $table->string('reference_number')->nullable();
            $table->decimal('collect_amount',10,2)->nullable();
            $table->date('collection_date')->nullable();
            $table->integer('collection_by')->nullable();
            $table->integer('cash_type')->nullable()->comment('0=cash,1=check');
            $table->date('check_date')->nullable();
            $table->string('check_number')->nullable();
            $table->string('status')->comment('0=>out 1=>in')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_collections');
    }
};
