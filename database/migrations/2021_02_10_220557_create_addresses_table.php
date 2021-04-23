<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->comment('用户id');
	    $table->string('name')->comment('收货姓名');
	     $table->char('addcode')->comment('城市码');
            $table->string('address')->comment('详细地址');
            $table->string('phone')->comment('手机号');
            $table->tinyInteger('is_default')->default(0)-> comment('默认地址');
            $table->timestamps();
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
