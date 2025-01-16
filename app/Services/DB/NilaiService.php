<?php

namespace App\Services\DB;

use App\Models\Process\NilaiDetail;
use App\Models\Process\RegisterEvent;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class NilaiService
{
    public static function new ()
    {
        return new NilaiDetail();
    }

    public static function Data($kd_event = "", $list = true)
    {
        $data = NilaiDetail::distinct();
        if (!empty($kd_event)) {
            $data = $data->where("nilaidetail.kd_event", DB::raw("{$kd_event}"));
        }
        if ($list) {
            $data = MstBobotService::Join($data, "nilaidetail.kd_event", "nilaidetail.kd_kriteria", "b");
            $data = MstKriteriaService::Join($data, "nilaidetail.kd_kriteria", "k");
        }
        return $data;
    }

    public static function Detail($kd_event, $kd_peserta, $action = "Edit")
    {
        $data = self::Data($kd_event, false)->where("kd_peserta", $kd_peserta)->first();
        if ($action == "Add") {
            if (!empty($data->kd_peserta)) {
                throw new \Exception(BaseService::MessageDataExists("Kode Event {$kd_event} and Kode Peserta {$kd_peserta}"), 400);
            }
        } else {
            if (empty($data->kd_peserta)) {
                throw new \Exception(BaseService::MessageNotFound("Kode Event {$kd_event} and Kode Peserta {$kd_peserta}"), 400);
            }
        }
        return $data;
    }

    public static function Join($data, $kd_event, $kd_peserta, $alias = "nilaidetail", $type = "join", $versi = "v1")
    {
        $data = $data->{$type}(with(new RegisterEvent)->getTable() . " AS {$alias}", function ($q) use ($alias, $versi, $kd_event, $kd_peserta) {
            $q->on("{$alias}.kd_event", "=", ($versi == "v2" ? DB::raw("{$kd_event}") : $kd_event));
            $q->on("{$alias}.kd_peserta", "=", $kd_peserta);
        });
        return $data;
    }
}
