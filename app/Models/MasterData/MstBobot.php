<?php

namespace App\Models\MasterData;

use App\Models\BaseModel;

class MstBobot extends BaseModel
{
    protected $table = 'mstbobot';
    protected $primaryKey = ['kd_event', "kd_kriteria"];
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fiellable = [
        "kd_event",
        "kd_kriteria",
        "bobot",
        "created_at",
        "updated_at",
    ];

}
