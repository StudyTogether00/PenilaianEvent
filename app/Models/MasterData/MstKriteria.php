<?php

namespace App\Models\MasterData;

use App\Models\BaseModel;

class MstKriteria extends BaseModel
{
    protected $table = 'mstkriteria';
    protected $primaryKey = 'kd';
    public $incrementing = true;

    protected $fiellable = [
        "kd",
        "kriteria",
        "tipe",
        "flag_active",
        "created_at",
        "updated_at",
    ];

}