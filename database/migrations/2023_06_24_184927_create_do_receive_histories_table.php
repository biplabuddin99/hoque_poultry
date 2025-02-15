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
        Schema::create('do_receive_histories', function (Blueprint $table) {
            $table->id();
            $table->string('do_id')->nullable();
            $table->string('do_rec_uni_code')->nullable();
            $table->string('do_detail_id')->nullable();
            $table->string('chalan_no')->nullable();
            $table->date('stock_date')->nullable();
            $table->integer('distributor_id')->nullable();
            $table->string('product_id')->nullable();
            $table->string('batch_id')->nullable();
            $table->integer('ctn')->nullable();
            $table->integer('pcs')->nullable();
            $table->integer('totalquantity_pcs')->nullable();
            $table->integer('quantity_free')->nullable();
            $table->decimal('dp_price',14,2)->nullable();
            $table->decimal('dp_pcs',14,2)->nullable();
            $table->decimal('subtotal_dp_pcs',14,2)->nullable();
            $table->decimal('tp_price',14,2)->nullable();
            $table->decimal('tp_free',14,2)->nullable();
            $table->decimal('mrp_price',14,2)->nullable();
            $table->date('ex_date')->nullable();
            $table->string('unit_style_id')->nullable();
            $table->string('adjust')->nullable();
            $table->string('remark')->nullable();
            $table->string('status')->nullable()->comment('0=out,1=in');
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
        Schema::dropIfExists('do_receive_histories');
    }
};
