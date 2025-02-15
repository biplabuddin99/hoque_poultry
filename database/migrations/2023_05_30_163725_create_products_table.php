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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('group_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('distributor_id')->nullable();
            $table->string('product_name')->nullable();
            $table->decimal('dp_price',14,2)->nullable();
            $table->decimal('tp_price',14,2)->nullable();
            $table->decimal('tp_free',14,2)->nullable();
            $table->decimal('mrp_price',14,2)->nullable();
            $table->decimal('free',14,2)->nullable();
            $table->decimal('free_ratio',14,2)->nullable();
            $table->decimal('free_taka',14,2)->nullable();
            $table->decimal('adjust',14,2)->nullable();
            $table->integer('unit_style_id')->nullable();
            $table->integer('base_unit')->nullable();
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->string('weight')->nullable();
            $table->string('image')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
