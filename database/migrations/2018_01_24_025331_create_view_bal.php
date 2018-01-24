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
        DB::statement("
            CREATE VIEW `bal` AS 
                select `atr`.`fromdoc` AS `fromdoc`,sum(((`atr`.`amt` * `gacc`.`dir`) * `gacc`.`gdir`)) AS `amt` 
                from (`atr` join `gacc`) 
                where (`atr`.`acc` = `gacc`.`accid`) 
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
