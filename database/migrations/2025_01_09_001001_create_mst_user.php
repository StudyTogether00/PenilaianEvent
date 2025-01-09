<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMstUser extends Migration
{
    public function __construct()
    {
        $this->info_table = with(new User);
        $this->tbl = $this->info_table->getTable();
    }

    public function up()
    {
        if (Schema::hasTable($this->tbl)) {return;}
        Schema::create($this->tbl, function (Blueprint $table) {
            $table->bigInteger('userid', true);
            $table->string('username', 25);
            $table->string('password', 50);
            $table->string('fullname', 100);
            $table->boolean("flag_active");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->tbl);
    }
}
