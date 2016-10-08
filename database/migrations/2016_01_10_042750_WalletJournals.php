<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\ApiKeys;
use Pheal\Pheal;

class WalletJournals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_journals', function (Blueprint $table) {
            $table->bigInteger('transactionID')->unsigned()->primary();
            $table->integer('user_id')->unsigned();
            $table->dateTime('transactionDateTime');
            $table->integer('quantity')->unsigned();
            $table->string('typeName');
            $table->integer('typeID')->unsigned();
            $table->decimal('price',14,2);
            $table->bigInteger('clientID')->unsigned();
            $table->string('clientName');
            $table->bigInteger('stationID')->unsigned();
            $table->string('stationName');
            $table->string('transactionType');
            $table->string('transactionFor');
            $table->bigInteger('journalTransactionID')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('wallet_journals');
    }
    public function refreshJournals() {
    }
}
