<?php

namespace App\Models\Process;

use App\Models\BaseModel;

class RegisterEvent extends BaseModel
{
    protected $table = 'registerevent';
    protected $primaryKey = ['kd_event', "kd_peserta"];
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fiellable = [
        "kd_event",
        "kd_peserta",
        "no_event",
        "tgl_register",
        "created_at",
        "updated_at",
    ];

}
