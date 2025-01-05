<?php

use App\Models\MasterData\MstBobot;
use App\Models\MasterData\MstEvent;
use App\Models\MasterData\MstKriteria;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMstBobot extends Migration
{
    public function __construct()
    {
        $this->info_table = with(new MstBobot);
        $this->tbl = $this->info_table->getTable();
    }

    public function up()
    {
        if (Schema::hasTable($this->tbl)) {return;}
        $r_event = with(new MstEvent)->getTable();
        $r_kriteria = with(new MstKriteria)->getTable();
        Schema::create($this->tbl, function (Blueprint $table) use ($r_event, $r_kriteria) {
            $table->bigInteger('kd_event');
            $table->bigInteger('kd_kriteria');
            $table->float('bobot');
            $table->timestamps();
            $table->primary(['kd_event', 'kd_kriteria']);

            $table->foreign('kd_event')->references('kd_event')->on($r_event)->constrained()->onDelete('no action')->onUpdate('no action');
            $table->foreign('kd_kriteria')->references('kd_kriteria')->on($r_kriteria)->constrained()->onDelete('no action')->onUpdate('no action');
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->tbl);
    }
}
