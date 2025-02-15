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
        Schema::create('check_details', function (Blueprint $table) {
            $table->id();
            $table->integer('shop_id')->nullable();
            $table->integer('sr_id')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('check_number')->nullable();
            $table->date('check_date')->nullable();
            $table->date('cash_date')->nullable();
            $table->string('check_status')->nullable();
            $table->string('memu_number')->nullable();
            $table->decimal('amount',10,2)->default(0)->nullable();
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
        Schema::dropIfExists('check_details');
    }
};
