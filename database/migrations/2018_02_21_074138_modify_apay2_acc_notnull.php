<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyApay2AccNotnull extends Migration
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
            $table->string('transaction_type', 50)->nullable(false)->change();
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
            $table->string('transaction_type', 50)->nullable()->change();
        });
    }
}
