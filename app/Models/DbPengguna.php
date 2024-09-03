<?php

namespace App\Models;

class DbPengguna extends BaseModel
{
    protected $table ='db_pengguna';
    protected $primaryKey ='kd';
    protected $keyType ='string';
    public $incrementing = false;

    protected $fiellable = [
        "kd",
        "nm_pengguna",
        "jns_kel",
        "tgl_lhr",
        "email",
        "username",
        "password",
        "created_at",
        "updated_at",

    ];

}