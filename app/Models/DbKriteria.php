<?php

namespace App\Models;

class DbKriteria extends BaseModel
{
    protected $table ='db_kriteria';
    protected $primaryKey ='kd';
    protected $keyType ='string';
    public $incrementing = false;

    protected $fiellable = [
        "kd",
        "kriteria",
        "tipe",
        "created_at",
        "updated_at",

    ];

}