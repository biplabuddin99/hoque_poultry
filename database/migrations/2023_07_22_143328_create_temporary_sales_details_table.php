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
        Schema::create('temporary_sales_details', function (Blueprint $table) {
            $table->id();
            $table->integer('tem_sales_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('ctn')->nullable();
            $table->integer('pcs')->nullable();
            $table->integer('totalquantity_pcs')->nullable();
            $table->string('select_tp_tpfree')->comment('1=>tp 2=>tpfree')->nullable();
            $table->decimal('ctn_price',14,2)->nullable();
            $table->decimal('pcs_price',14,2)->nullable();
            $table->decimal('subtotal_price',14,2)->nullable();
            $table->string('status')->default(0);
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
        Schema::dropIfExists('temporary_sales_details');
    }
};
