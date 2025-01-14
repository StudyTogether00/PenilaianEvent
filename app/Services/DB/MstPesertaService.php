<?php

namespace App\Services\DB;

use App\Models\MasterData\MstPeserta;
use App\Services\BaseService;

class MstPesertaService
{
    public static function new ()
    {
        return new MstPeserta();
    }

    public static function Data($flag_active = 1)
    {
        $data = MstPeserta::distinct();
        if (!empty($flag_active) || $flag_active === 0) {
            $data = $data->where("mstpeserta.flag_active", $flag_active);
        }
        return $data;
    }

    public static function Detail($kd_peserta, $flag_active = 1, $action = "Edit")
    {
        $data = self::Data($flag_active)->find($kd_peserta);
        if ($action == "Add") {
            if (!empty($data->kd_peserta)) {
                throw new \Exception(BaseService::MessageDataExists("Kode Peserta {$kd_peserta}"), 400);
            }
        } else {
            if (empty($data->kd_peserta)) {
                throw new \Exception(BaseService::MessageNotFound("Kode Peserta {$kd_peserta}"), 400);
            }
        }
        return $data;
    }

    public static function Join($data, $kd_peserta, $alias = "mstpeserta", $type = "join")
    {
        $data = $data->{$type}(with(new MstPeserta)->getTable() . " AS {$alias}", function ($q) use ($alias, $kd_peserta) {
            $q->on("{$alias}.kd_peserta", "=", $kd_peserta);
        });
        return $data;
    }
}
