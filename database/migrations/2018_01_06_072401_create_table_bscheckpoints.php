<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateTableBscheckpoints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('bscheckpoints', function (Blueprint $table) {
            
            $table->increments('id');

            $table->date('checkdate');
            
            $table->integer('recid')->unsigned();
            
            $table->decimal('amt',8,2);

            $table->foreign('recid')->references('id')->on('reconcile');
            // $table->integer('userid')->unsigned();
            // $table->foreign('userid')->references('id')->on('users');
            $table->unique('checkdate','recid');

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
        //
    }
}
