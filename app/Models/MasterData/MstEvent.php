<?php

namespace App\Models\MasterData;

use App\Models\BaseModel;

class MstEvent extends BaseModel
{
    protected $table = 'mstevent';
    protected $primaryKey = 'kd_event';
    public $incrementing = true;

    protected $fiellable = [
        "kd_event",
        "nm_event",
        "tgl_event",
        "kuota",
        "flag_active",
        "created_at",
        "updated_at",
    ];

}
