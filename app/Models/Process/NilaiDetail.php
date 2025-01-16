<?php

namespace App\Models\Process;

use App\Models\BaseModel;

class NilaiDetail extends BaseModel
{
    protected $table = 'nilaidetail';
    protected $primaryKey = ['kd_event', "kd_kriteria", "kd_peserta"];
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fiellable = [
        "kd_event",
        "kd_kriteria",
        "kd_peserta",
        "nilai",
        "created_at",
        "updated_at",
    ];

}
