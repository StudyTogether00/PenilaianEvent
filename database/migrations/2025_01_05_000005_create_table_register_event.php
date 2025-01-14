<?php

use App\Models\MasterData\MstEvent;
use App\Models\MasterData\MstPeserta;
use App\Models\Process\RegisterEvent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableRegisterEvent extends Migration
{
    public function __construct()
    {
        $this->info_table = with(new RegisterEvent);
        $this->tbl = $this->info_table->getTable();
    }

    public function up()
    {
        if (Schema::hasTable($this->tbl)) {return;}
        $r_event = with(new MstEvent)->getTable();
        $r_peserta = with(new MstPeserta)->getTable();
        Schema::create($this->tbl, function (Blueprint $table) use ($r_event, $r_peserta) {
            $table->bigInteger('kd_event');
            $table->bigInteger('kd_peserta');
            $table->string('no_event', 50);
            $table->date('tgl_register');
            $table->timestamps();

            $table->primary(['kd_event', 'kd_peserta']);
            $table->foreign('kd_event')->references('kd_event')->on($r_event)->constrained()->onDelete('no action')->onUpdate('no action');
            $table->foreign('kd_peserta')->references('kd_peserta')->on($r_peserta)->constrained()->onDelete('no action')->onUpdate('no action');
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->tbl);
    }
}
