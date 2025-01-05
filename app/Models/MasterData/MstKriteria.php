<?php

namespace App\Models\MasterData;

use App\Models\BaseModel;

class MstKriteria extends BaseModel
{
    protected $table = 'mstkriteria';
    protected $primaryKey = "kd_kriteria";
    public $incrementing = true;

    protected $fiellable = [
        "kd_kriteria",
        "nm_kriteria",
        "tipe",
        "flag_active",
        "created_at",
        "updated_at",
    ];

}
