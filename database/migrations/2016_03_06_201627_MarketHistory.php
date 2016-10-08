<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketHistory', function (Blueprint $table) {
            $table->integer('typeID')->unsigned()->primary();
            $table->decimal('lowPrice',14,2);
            $table->decimal('avgPrice',14,2);
            $table->decimal('highPrice',14,2);
            $table->integer('volume')->unsigned();
            $table->integer('orderCount')->unsigned();
            $table->date('updated_at');
            $table->mediumText('history');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return voids
     */
    public function down()
    {
        Schema::drop('marketHistory');
    }
}
