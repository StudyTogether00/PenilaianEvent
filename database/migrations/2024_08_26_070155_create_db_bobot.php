<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDbBobot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_bobot', function (Blueprint $table) {
            $table->string('kd_event',10);
            $table->string('kd_kriteria',10);
            $table->float('bobot');
            $table->timestamps();

            $table->primary(['kd_event','kd_kriteria']);

            $table->foreign('kd_event')
                  ->references('kd')
                  ->on('db_event')
                  ->constrained()
                  ->onDelete('no action')
                  ->onUpdate('no action');

            $table->foreign('kd_kriteria')
                  ->references('kd')
                  ->on('db_kriteria')
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
        Schema::dropIfExists('db_bobot');
    }
}
