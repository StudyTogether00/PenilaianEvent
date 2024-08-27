<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDbPeserta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_peserta', function (Blueprint $table) {
            $table->string('kd',10);
            $table->string('nm_peserta',100);
            $table->boolean('jns_kel');
            $table->date('tgl_lhr');
            $table->string('alamat',50);
            $table->string('email',100);
            $table->string('username',25);
            $table->string('password',50);
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
        Schema::dropIfExists('db_peserta');
    }
}
