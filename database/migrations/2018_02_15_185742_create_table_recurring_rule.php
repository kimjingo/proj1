<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRecurringRule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('recurring', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->date('startdate');
            $table->date('enddate');
            $table->date('lastposted_date')->nullable();
            $table->integer('recurringdate');
            $table->integer('recurringmonth')->nullable();
            $table->enum('cycle',['biennially','yearly','biannually','quarterly','bimonthly','monthly','biweekly','weekly','daily','hourly']);
            $table->enum('type',['expense','storage','others']);
            $table->string('vendor');
            $table->string('material');
            $table->decimal('amt',8,2);
            $table->string('clearing');
            $table->timestamps();
            $table->unique(['vendor','material','type','startdate']);
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recurring');


    }
}
