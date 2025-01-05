<?php

use App\Models\MasterData\MstEvent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMstEvent extends Migration
{
    public function __construct()
    {
        $this->info_table = with(new MstEvent);
        $this->tbl = $this->info_table->getTable();
    }

    public function up()
    {
        if (Schema::hasTable($this->tbl)) {return;}
        Schema::create($this->tbl, function (Blueprint $table) {
            $table->bigInteger('kd_event', true);
            $table->string('nm_event', 50);
            $table->date('tgl_event');
            $table->integer('kuota');;
            $table->boolean("flag_active");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->tbl);
    }
}
