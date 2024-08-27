<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDbKriteria extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_kriteria', function (Blueprint $table) {
            $table->string('kd',10);
            $table->string('kriteria',25);
            $table->boolean('tipe');
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
        Schema::dropIfExists('db_kriteria');
    }
}
