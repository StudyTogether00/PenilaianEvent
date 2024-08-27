<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDbPesertaEvent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_peserta_event', function (Blueprint $table) {
            $table->string('kd_peserta',10);
            $table->string('kd_event',10);
            $table->timestamps();

            $table->primary(['kd_peserta','kd_event']);

            $table->foreign('kd_peserta')
                  ->references('kd')
                  ->on('db_peserta')
                  ->constrained()
                  ->onDelete('no action')
                  ->onUpdate('no action');

            $table->foreign('kd_event')
                  ->references('kd')
                  ->on('db_event')
                  ->constrained()
                  ->onDelete('no action')
                  ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('db_peserta_event');
    }
}
