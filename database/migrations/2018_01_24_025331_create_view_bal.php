<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewBal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (Schema::hasTable('bal')) {
            //
            Schema::dropIfExists('bal');

        }

        DB::statement("
            CREATE VIEW `bal` AS 
                select `atr`.`fromdoc` AS `fromdoc`,sum(((`atr`.`amt` * `gacc`.`dir`) * `gacc`.`gdir`)) AS `amt` 
                from (`atr` join `gacc`) 
                where (`atr`.`acc` = `gacc`.`accid`) AND pdate >= '2017-01-01'
                group by `atr`.`fromdoc`
        ");
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
