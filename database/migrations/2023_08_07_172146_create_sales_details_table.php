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
        Schema::create('sales_details', function (Blueprint $table) {
            $table->id();
            $table->integer('sales_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('ctn')->nullable();
            $table->integer('pcs')->nullable();
            $table->integer('ctn_return')->nullable();
            $table->integer('pcs_return')->nullable();
            $table->integer('ctn_damage')->nullable();
            $table->integer('pcs_damage')->nullable();
            $table->decimal('ctn_price',14,2)->nullable();
            $table->decimal('tp_price',14,2)->nullable();
            $table->decimal('tp_free',14,2)->nullable();
            $table->decimal('total_return_pcs',14,2)->nullable();
            $table->decimal('total_damage_pcs',14,2)->nullable();
            $table->decimal('total_sales_pcs',14,2)->nullable();
            $table->decimal('subtotal_price',14,2)->nullable();
            // $table->string('select_tp_tpfree')->comment('1=>tp 2=>tpfree')->nullable();
            $table->string('status')->comment('0=return,1=oldreturn');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('sales_details');
    }
};
