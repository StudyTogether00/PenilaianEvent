<?php

namespace App\Services\DB;

use App\Models\MasterData\MstBobot;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class MstBobotService
{
    public static function new ()
    {
        return new MstBobot();
    }

    public static function Data($kd_event = "", $list = false)
    {
        $data = MstBobot::distinct();
        if (!empty($kd_event)) {
            $data = $data->where("mstbobot.kd_event", DB::raw("{$kd_event}"));
        }
        if ($list) {
            $data = MstKriteriaService::Join($data, "mstbobot.kd_kriteria", "mk");
        }
        return $data;
    }

    public static function Detail($kd_event, $kd_kriteria, $action = "Edit")
    {
        $data = self::Data($kd_event)->where("kd_kriteria", $kd_kriteria)->first();
        if ($action == "Add") {
            if (!empty($data->kd_event)) {
                throw new \Exception(BaseService::MessageDataExists("Kode Event {$kd_event} and Kode Kriteria {$kd_kriteria}"), 400);
            }
        } else {
            if (empty($data->kd_event)) {
                throw new \Exception(BaseService::MessageNotFound("Kode Event {$kd_event} and Kode Kriteria {$kd_kriteria}"), 400);
            }
        }
        return $data;
    }

    public static function Join($data, $kd_event, $kd_kriteria, $alias = "mstbobot", $type = "join", $versi = "v1")
    {
        $data = $data->{$type}(with(new MstBobot)->getTable() . " AS {$alias}", function ($q) use ($alias, $versi, $kd_event, $kd_kriteria) {
            $q->on("{$alias}.kd_event", "=", ($versi == "v2" ? DB::raw("{$kd_event}") : $kd_event));
            $q->on("{$alias}.kd_kriteria", "=", $kd_kriteria);
        });
        return $data;
    }
}
