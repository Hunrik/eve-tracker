<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApiCreds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apikeys', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key',16)->unique();
            $table->string('vCode',64);
            $table->integer('user_id')->unsigned();
            $table->json('characters');
            $table->json('access');
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
        Schema::drop('apikeys');
    }
}
