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
        Schema::create('return_products', function (Blueprint $table) {
            $table->id();
            $table->string('driver_name')->nullable();
            $table->string('helper')->nullable();
            $table->string('garir_number')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('note')->nullable();
            $table->decimal('sub_total',14,2)->nullable();
            $table->decimal('vat_amount',10,2)->nullable();
            $table->decimal('discount_amount',10,2)->nullable();
            $table->decimal('other_charge',14,2)->nullable();
            $table->decimal('paid',14,2)->nullable();
            $table->decimal('total',14,2)->nullable();
            $table->string('status')->default(0)->comment('0=out,1=in');
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
        Schema::dropIfExists('return_products');
    }
};
