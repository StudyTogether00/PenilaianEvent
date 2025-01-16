<?php

use App\Models\MasterData\MstBobot;
use App\Models\Process\NilaiDetail;
use App\Models\Process\RegisterEvent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableNilaiDetail extends Migration
{
    public function __construct()
    {
        $this->info_table = with(new NilaiDetail);
        $this->tbl = $this->info_table->getTable();
    }

    public function up()
    {
        if (Schema::hasTable($this->tbl)) {return;}
        $r_regis = with(new RegisterEvent)->getTable();
        $r_bobot = with(new MstBobot)->getTable();
        Schema::create($this->tbl, function (Blueprint $table) use ($r_regis, $r_bobot) {
            $table->bigInteger('kd_event');
            $table->bigInteger('kd_kriteria');
            $table->bigInteger('kd_peserta');
            $table->float('nilai');
            $table->timestamps();

            $table->primary(['kd_event', "kd_kriteria", 'kd_peserta']);
            $table->foreign(['kd_event', 'kd_peserta'])->references(['kd_event', 'kd_peserta'])->on($r_regis)->constrained()->onDelete('no action')->onUpdate('no action');
            $table->foreign(['kd_event', "kd_kriteria"])->references(['kd_event', "kd_kriteria"])->on($r_bobot)->constrained()->onDelete('no action')->onUpdate('no action');
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->tbl);
    }
}
