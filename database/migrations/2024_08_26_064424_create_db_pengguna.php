<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDbPengguna extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_pengguna', function (Blueprint $table) {
            $table->string('kd',10)->primary();
            $table->string('nm_pengguna',100);
            $table->boolean('jns_kel');
            $table->date('tgl_lhr');
            $table->string('email',100);
            $table->string('username',50);
            $table->string('password',50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('db_pengguna');
    }
}
