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
        Schema::create('temporary_sales', function (Blueprint $table) {
            $table->id();
            $table->string('select_shop_dsr')->nullable();
            $table->string('shop_id')->nullable();
            $table->string('dsr_id')->nullable();
            $table->integer('sr_id')->nullable();
            $table->integer('distributor_id')->nullable();
            $table->date('sales_date')->nullable();
            $table->decimal('total',14,2)->nullable();
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
        Schema::dropIfExists('temporary_sales');
    }
};
