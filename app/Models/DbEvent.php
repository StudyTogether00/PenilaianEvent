<?php

namespace App\Models;

class DbEvent extends BaseModel
{
    protected $table ='db_event';
    protected $primaryKey ='kd';
    protected $keyType ='string';
    public $incrementing = false;

    protected $fiellable = [
        "kd",
        "nm_event",
        "kuota",
        "tgl_regist",
        "akhir_regist",
        "tgl_mulai",
        "tgl_akhir",
        "created_at",
        "updated_at",

    ];

}