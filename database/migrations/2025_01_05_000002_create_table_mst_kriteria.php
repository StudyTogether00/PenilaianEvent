<?php

use App\Models\MasterData\MstKriteria;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMstKriteria extends Migration
{
    public function __construct()
    {
        $this->info_table = with(new MstKriteria);
        $this->tbl = $this->info_table->getTable();
    }

    public function up()
    {
        if (Schema::hasTable($this->tbl)) {return;}
        Schema::create($this->tbl, function (Blueprint $table) {
            $table->bigInteger('kd_kriteria', true);
            $table->string('nm_kriteria', 50);
            $table->integer('tipe'); // 1 => Benefit; 0 => Cost
            $table->boolean("flag_active");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->tbl);
    }
}
