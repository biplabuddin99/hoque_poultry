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
        Schema::create('display_products', function (Blueprint $table) {
            $table->id();
            $table->integer('sales_id');
            $table->integer('product_id');
            $table->date('date')->nullable();
            $table->integer('group_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('shop_name')->nullable();
            $table->decimal('price',10,2)->default(0)->nullable();
            $table->decimal('total_price',10,2)->default(0)->nullable();
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
        Schema::dropIfExists('display_products');
    }
};
