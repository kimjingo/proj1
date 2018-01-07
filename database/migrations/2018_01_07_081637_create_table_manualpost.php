<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateTableManualpost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('manualposts', function (Blueprint $table) {
            
            $table->increments('id');

            $table->date('pdate');
            
            $table->decimal('amt',8,2);

            $table->char('mp',20)->nullable();
            
            $table->char('cr',20)->nullable();
            $table->tinyinteger('cr_dir')->nullable();
            $table->char('cr_clearing',20)->nullable();

            $table->char('dr',20)->nullable();
            $table->tinyinteger('dr_dir')->nullable();
            $table->char('dr_clearing',20)->nullable();

            $table->char('material',20)->nullable();
            $table->char('remark',255)->nullable();
            $table->char('ttype',20)->nullable();
            $table->char('checkno',10)->nullable();

            $table->date('posting')->nullable();
            $table->char('paidby',20)->nullable();
            $table->tinyinteger('ba')->nullable();

            $table->timestamps();

            // $table->integer('userid')->unsigned();
            // $table->foreign('userid')->references('id')->on('users');

            $table->unique(['pdate','amt','mp','remark','checkno','ttype']);
        });


        if (Schema::hasTable('manualpost')) {
            //
            $dbresult = DB::insert('INSERT INTO manualposts SELECT no,tdate,amt,mp,cr,cr_dir,cr_clearing,dr,dr_dir,dr_clearing,material,remark ,ttype,checkno,posting,paidby,ba,now(),now() FROM manualpost');
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
