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
        Schema::create('shop_balances', function (Blueprint $table) {
            $table->id();
            $table->string('shop_id')->nullable();
            $table->string('sales_id')->nullable();
            $table->decimal('balance_amount',10,2)->nullable();
            $table->string('reference_number')->nullable();
            $table->integer('cash_type')->nullable()->comment('0=cash,1=check,2=bank,3=due');
            $table->integer('check_type')->nullable();
            $table->date('check_date')->nullable();
            $table->date('new_due_date')->nullable();
            $table->date('sr_id')->nullable();
            $table->string('status')->comment('0=>out 1=>in')->nullable();
            // $table->string('status_history')->nullable()->comment('0=out,1=in');
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
        Schema::dropIfExists('shop_balances');
    }
};
