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
        Schema::create('return_receive_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('return_product_id')->nullable();
            $table->integer('group_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('unite_style_id')->nullable();
            $table->integer('return_type')->nullable();
            $table->integer('ctn_return')->nullable();
            $table->integer('pcs_return')->nullable();
            $table->integer('total_pcs_return')->nullable();
            $table->integer('total_receive_qty')->nullable();
            $table->decimal('price',14,2)->nullable();
            $table->decimal('amount',14,2)->nullable();
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
        Schema::dropIfExists('return_receive_histories');
    }
};
