<?php

namespace App\Services\DB;

use App\Models\MasterData\MstKriteria;
use App\Services\BaseService;

class MstKriteriaService
{
    public static function new ()
    {
        return new MstKriteria();
    }

    public static function Data($flag_active = 1)
    {
        $data = MstKriteria::distinct();
        if (!empty($flag_active) || $flag_active === 0) {
            $data = $data->where("mstkriteria.flag_active", $flag_active);
        }
        return $data;
    }

    public static function Detail($kd_kriteria, $flag_active = 1, $action = "Edit")
    {
        $data = self::Data($flag_active)->find($kd_kriteria);
        if ($action == "Add") {
            if (!empty($data->kd_kriteria)) {
                throw new \Exception(BaseService::MessageDataExists("Kode Kriteria {$kd_kriteria}"), 400);
            }
        } else {
            if (empty($data->kd_kriteria)) {
                throw new \Exception(BaseService::MessageNotFound("Kode Kriteria {$kd_kriteria}"), 400);
            }
        }
        return $data;
    }

    public static function Join($data, $kd_kriteria, $alias = "mstkriteria", $type = "join")
    {
        $data = $data->{$type}(with(new MstKriteria())->getTable() . " AS {$alias}", function ($q) use ($alias, $kd_kriteria) {
            $q->on("{$alias}.kd_kriteria", "=", $kd_kriteria);
        });
        return $data;
    }
}
