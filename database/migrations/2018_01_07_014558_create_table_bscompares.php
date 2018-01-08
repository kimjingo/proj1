<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBscompares extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('bsyymm', function (Blueprint $table) {
            
            $table->increments('id');

            $table->date('yymm');
            
            $table->string('accid');
            
            $table->decimal('amt',8,2);
            
            // $table->foreign('accid')->references('accid')->on('gacc');

            // $table->integer('userid')->unsigned();
            $table->unique(['yymm','accid']);

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
