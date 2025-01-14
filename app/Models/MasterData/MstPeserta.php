<?php

namespace App\Models\MasterData;

use App\Models\BaseModel;

class MstPeserta extends BaseModel
{
    protected $table = 'mstpeserta';
    protected $primaryKey = "kd_peserta";
    public $incrementing = true;

    protected $fiellable = [
        "kd_peserta",
        "nm_peserta",
        "jns_kel",
        "tgl_lhr",
        "alamat",
        "email",
        "username",
        "password",
        "flag_active",
        "created_at",
        "updated_at",
    ];

}
