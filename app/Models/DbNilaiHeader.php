<?php

namespace App\Models;

class DbNilaiHeader extends BaseModel
{
    protected $table ='db_nilai_header';
    protected $primaryKey =['kd_peserta','kd_event'];
    protected $keyType ='string';
    public $incrementing = false;

    protected $fiellable = [
        "kd_peserta",
        "kd_event",
        "nilai_rata2",
        "created_at",
        "updated_at",

    ];

}