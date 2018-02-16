<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyRecurringApay2Acc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('apay2_acc', function (Blueprint $table) {
            //
            $table->decimal('rate', 5, 4)->default(1);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('apay2_acc', function (Blueprint $table) {
            //
            $table->dropColumn('rate');

        });

    }
}
