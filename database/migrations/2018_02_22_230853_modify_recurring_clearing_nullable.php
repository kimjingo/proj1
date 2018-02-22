<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyRecurringClearingNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recurring', function (Blueprint $table) {
            //
            // $table->string('clearing',100)->nullable();
            DB::statement('alter table recurring change clearing clearing varchar(50) NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recurring', function (Blueprint $table) {
            //
            // $table->string('clearing',100)->nullable(false);
            DB::statement('alter table recurring change clearing clearing varchar(50) NOT NULL');

        });
    }
}
