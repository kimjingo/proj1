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
            //;
            //alter table apay2_acc change amount_type amount_type varchar(20) default '';
            //alter table apay2_acc change amount_description amount_description varchar(40) default '';
            DB::statement('alter table apay2_acc change transaction_type transaction_type varchar(50) default ""');
            DB::statement('alter table apay2_acc change amount_type amount_type varchar(20) default ""');
            DB::statement('alter table apay2_acc change amount_description amount_description varchar(40) default ""');

            //alter table manualposts change paidby paidby varchar(20) default '';
            //alter table manualposts change mp mp varchar(20) default '';
            //alter table manualposts change material material varchar(20) default '';
            DB::statement('alter table manualposts change paidby paidby varchar(20) default ""');
            DB::statement('alter table manualposts change mp mp varchar(20) default ""');
            DB::statement('alter table manualposts change material material varchar(20) default ""');
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
