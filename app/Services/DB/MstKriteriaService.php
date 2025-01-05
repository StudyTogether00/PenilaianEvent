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

    public static function Detail($kd, $flag_active = 1, $action = "Edit")
    {
        $data = self::Data($flag_active)->find($kd);
        if ($action == "Add") {
            if (!empty($data->kd)) {
                throw new \Exception(BaseService::MessageDataExists("Kode Kriteria {$kd}"), 400);
            }
        } else {
            if (empty($data->kd)) {
                throw new \Exception(BaseService::MessageNotFound("Kode Kriteria {$kd}"), 400);
            }
        }
        return $data;
    }

    public static function Join($data, $kd, $alias = "mstkriteria", $type = "join")
    {
        $data = $data->{$type}(with(new MstKriteria())->getTable() . " AS {$alias}", function ($q) use ($alias, $kd) {
            $q->on("{$alias}.kd", "=", $kd);
        });
        return $data;
    }
}