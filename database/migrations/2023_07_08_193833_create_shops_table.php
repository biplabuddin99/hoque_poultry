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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('shop_name')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('area_name')->nullable();
            $table->string('dsr_id')->nullable();
            $table->string('sr_id')->nullable();
            $table->string('sup_id')->nullable();
            $table->string('contact')->nullable();
            $table->string('address')->nullable();
            $table->decimal('balance',14,2)->nullable();
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
        Schema::dropIfExists('shops');
    }
};
