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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('shop_id')->nullable();
            $table->string('dsr_id')->nullable();
            $table->integer('sr_id')->nullable();
            $table->integer('distributor_id')->nullable();
            $table->integer('tem_sales_id')->nullable();
            $table->date('sales_date')->nullable();
            // $table->integer('product_id')->nullable();
            // $table->integer('ctn')->nullable();
            // $table->integer('pcs')->nullable();
            // $table->integer('tp')->nullable();
            // $table->decimal('tp_price',14,2)->nullable();
            $table->decimal('daily_total_taka',14,2)->nullable();
            $table->decimal('return_total_taka',14,2)->nullable();
            $table->decimal('expenses',14,2)->nullable();
            $table->decimal('commission',14,2)->nullable();
            $table->decimal('dsr_cash',14,2)->nullable();
            $table->decimal('dsr_salary',14,2)->nullable();
            $table->decimal('final_total',14,2)->nullable();
            $table->decimal('today_final_cash',14,2)->nullable();
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
        Schema::dropIfExists('sales');
    }
};
