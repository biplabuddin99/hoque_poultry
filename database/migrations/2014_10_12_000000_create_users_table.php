<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('contact_no')->unique();
            $table->unsignedBigInteger('role_id')->index();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->string('password');
            $table->integer('distributor_id')->nullable();
            $table->integer('sr_id')->nullable();
            $table->string('language')->default('en');
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->string('image')->nullable();
            $table->boolean('status')->default(1)->comment('1=>active 2=>inactive');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        // DB::table('users')->insert([
        //     [
        //         'name' => 'Admin',
        //         'email' => 'admin@email.com',
        //         'contact_no' => '16247',
        //         'password' => Hash::make('superadmin16247'),
        //         'role_id' => 2,
        //         'company_id' => 1,
        //         'created_at' => Carbon::now()
        //     ]
        // ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
