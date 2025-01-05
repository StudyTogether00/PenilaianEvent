<?php

use App\Models\MasterData\MstKriteria;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMstKriteria extends Migration
{
    public function __construct()
    {
        $this->info_table = with(new MstKriteria());
        $this->tbl = $this->info_table->getTable();
    }

    public function up()
    {
        if (Schema::hasTable($this->tbl)) {return;}
        Schema::create($this->tbl, function (Blueprint $table) {
            $table->string('kd', 10, true);
            $table->string('kriteria', 25);
            $table->date('tipe');;
            $table->boolean("flag_active");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->tbl);
    }
}
