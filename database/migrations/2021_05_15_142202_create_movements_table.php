<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('movement_type_id');
            $table->integer('bill_100000')->default(0);
            $table->integer('bill_50000')->default(0);
            $table->integer('bill_20000')->default(0);
            $table->integer('bill_10000')->default(0);
            $table->integer('bill_5000')->default(0);
            $table->integer('bill_2000')->default(0);
            $table->integer('bill_1000')->default(0);
            $table->integer('coin_1000')->default(0);
            $table->integer('coin_500')->default(0);
            $table->integer('coin_200')->default(0);
            $table->integer('coin_100')->default(0);
            $table->integer('coin_50')->default(0);
            $table->timestamps();

            $table->foreign('movement_type_id')->references('id')->on('movement_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movements');
    }
}
