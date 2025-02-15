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
        Schema::create('d_o_details', function (Blueprint $table) {
            $table->id();
            $table->integer('do_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->decimal('qty',14,2)->default(0)->nullable();
            $table->decimal('qty_pcs',14,2)->default(0)->nullable();
            $table->decimal('add_qty_pcs',14,2)->default(0)->nullable();
            $table->decimal('dp',14,2)->default(0)->comment('ctnDp')->nullable();
            $table->decimal('dp_pcs',14,2)->default(0)->nullable();
            $table->decimal('sub_total',14,2)->default(0)->nullable();
            $table->decimal('receive_qty',14,2)->default(0)->nullable();
            $table->decimal('receive_free_qty',14,2)->default(0)->nullable();
            $table->integer('unite_style_id')->nullable();
            $table->decimal('free',14,2)->default(0)->nullable();
            $table->decimal('free_tk',14,2)->default(0)->nullable();
            $table->decimal('free_ratio',14,2)->default(0)->nullable();

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
        Schema::dropIfExists('d_o_details');
    }
};
