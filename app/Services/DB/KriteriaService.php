<?php

namespace App\Services\DB;

use App\Models\MasterData\Jurusan;
use App\Services\BaseService;

class KriteriaService
{
    public static function new (): Kriteria
    {
        return new Kriteria();
    }

    public static function Data()
    {
        $data = Kriteria::distinct();
        return $data;
    }

    public static function Detail($kd, $action = "Edit")
    {
        $data = self::Data()->find($kd);
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

    public static function Join($data, $kd, $alias = "kriteria", $type = "join")
    {
        $data = $data->{$type}(with(new Kriteria)->getTable() . " AS {$alias}", function ($q) use ($alias, $kd) {
            $q->on("{$alias}.kd", "=", $kd);
        });
        return $data;
    }
}