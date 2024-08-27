<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDbEvent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_event', function (Blueprint $table) {
            $table->string('kd',10);
            $table->string('nm_event',25);
            $table->integer('kuota');
            $table->date('tgl_regist');
            $table->date('akhir_regist');
            $table->date('tgl_mulai');
            $table->date('tgl_akhir');
            $table->timestamps();
            $table->primary('kd');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('db_event');
    }
}
