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
        Schema::create('return_product_details', function (Blueprint $table) {
            $table->id();
            $table->integer('return_product_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('qty')->nullable();
            $table->integer('unite_style_id')->nullable();
            $table->decimal('free',14,2)->nullable();
            $table->decimal('free_tk',14,2)->nullable();
            $table->decimal('free_ratio',14,2)->nullable();
            $table->decimal('price',14,2)->nullable();
            $table->decimal('basic',14,2)->nullable();
            $table->decimal('discount_percent',14,2)->nullable();
            $table->decimal('vat_percent',14,2)->nullable();
            $table->decimal('amount',14,2)->nullable();
            $table->string('status')->default(0);
            $table->unsignedBigInteger('company_id')->nullable();
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
        Schema::dropIfExists('return_product_details');
    }
};
