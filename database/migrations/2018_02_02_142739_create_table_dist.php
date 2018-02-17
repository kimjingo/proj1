<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasTable('dist')) {
     // create the tblCategory table
            Schema::create('dist', function (Blueprint $table) {
                
                $table->increments('id');

                $table->date('posted_at');
                $table->integer('aid');
                
                $table->string('dd',1000);

                $table->timestamps();

                // $table->integer('userid')->unsigned();
                // $table->foreign('userid')->references('id')->on('users');

                $table->unique(['aid']);
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
        //
    }
}
