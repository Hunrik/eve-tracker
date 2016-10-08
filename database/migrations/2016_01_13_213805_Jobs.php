<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Jobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('sell_list')) {
            Schema::create('sell_list', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->unsigned();
                $table->foreign('user_id')->references('id')->on('users');
                $table->string('typeName');
                $table->integer('typeID')->unsigned();
                $table->integer('quantity')->unsigned();
                $table->integer('left');
                $table->decimal('price', 14, 2);
                $table->timestamps();
            });
        }
        if (!Schema::hasColumn('wallet_journals', 'sell_id')) {
            Schema::table('wallet_journals', function ($table) {
                $table->integer('sell_id')->unsigned()->nullable()->default(null);
                $table->foreign('sell_id')->references('id')->on('sell_list');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sell_list');
        Schema::table('wallet_journals', function ($table) {
            $table->dropColumn('sell_id');
        });
    }

    public function refreshJournals()
    {
    }
}
