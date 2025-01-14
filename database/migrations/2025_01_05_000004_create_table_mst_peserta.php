<?php

use App\Models\MasterData\MstPeserta;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMstPeserta extends Migration
{
    public function __construct()
    {
        $this->info_table = with(new MstPeserta);
        $this->tbl = $this->info_table->getTable();
    }

    public function up()
    {
        if (Schema::hasTable($this->tbl)) {return;}
        Schema::create($this->tbl, function (Blueprint $table) {
            $table->bigInteger('kd_peserta', true);
            $table->string('nm_peserta', 100);
            $table->integer('jns_kel'); // 1 => Laki-Laki 0 => Perempuan
            $table->date('tgl_lhr');
            $table->string('alamat', 200);
            $table->string('email', 50);
            $table->string('username', 50);
            $table->string('password', 50);
            $table->boolean("flag_active");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->tbl);
    }
}
