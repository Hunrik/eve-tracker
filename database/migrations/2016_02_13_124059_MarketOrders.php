<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('marketOrders', function (Blueprint $table) {
        $table->bigInteger('orderID')->unsigned()->primary();
        $table->integer('user_id')->unsigned();
        $table->integer('volEntered')->unsigned();
        $table->integer('volRemaining')->unsigned();
        $table->tinyInteger('orderState')->unsigned();
        $table->integer('typeID')->unsigned();
        $table->decimal('price',14,2);
        $table->boolean('bid')->index();
        $table->dateTime('issued');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('marketOrders');
    }
}
