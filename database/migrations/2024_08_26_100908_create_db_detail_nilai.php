<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDbDetailNilai extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_detail_nilai', function (Blueprint $table) {
            $table->string('kd_peserta',10);
            $table->string('kd_event',10);
            $table->string('kd_kriteria',10);
            $table->string('kd_event1',10);
            $table->float('nilai');
            $table->timestamps();
            
            $table->primary(['kd_peserta','kd_event','kd_kriteria','kd_event1'], 'pk_db_detail_nilai');

            $table->foreign(['kd_peserta','kd_event'])
                  ->references(['kd_peserta','kd_event'])
                  ->on('db_nilai_header')
                  ->constrained()
                  ->onDelete('no action')
                  ->onUpdate('no action');

            $table->foreign(['kd_kriteria','kd_event1'])
                  ->references(['kd_kriteria','kd_event'])
                  ->on('db_bobot')
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
        Schema::dropIfExists('db_detail_nilai');
    }
}
