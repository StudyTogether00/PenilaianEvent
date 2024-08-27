<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDbNilaiHeader extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_nilai_header', function (Blueprint $table) {
            $table->string('kd_peserta',10);
            $table->string('kd_event',10);
            $table->float('nilai_rata2');
            $table->timestamps();

            $table->primary(['kd_peserta','kd_event']);

            $table->foreign(['kd_peserta','kd_event'])
                  ->references(['kd_peserta','kd_event'])
                  ->on('db_peserta_event')
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
        Schema::dropIfExists('db_nilai_header');
    }
}
