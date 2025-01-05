<?php

namespace App\Services\DB;

use App\Models\MasterData\MstEvent;
use App\Services\BaseService;

class MstEventService
{
    public static function new ()
    {
        return new MstEvent();
    }

    public static function Data($flag_active = 1)
    {
        $data = MstEvent::distinct();
        if (!empty($flag_active) || $flag_active === 0) {
            $data = $data->where("mstevent.flag_active", $flag_active);
        }
        return $data;
    }

    public static function Detail($kd_event, $flag_active = 1, $action = "Edit")
    {
        $data = self::Data($flag_active)->find($kd_event);
        if ($action == "Add") {
            if (!empty($data->kd_event)) {
                throw new \Exception(BaseService::MessageDataExists("Kode Event {$kd_event}"), 400);
            }
        } else {
            if (empty($data->kd_event)) {
                throw new \Exception(BaseService::MessageNotFound("Kode Event {$kd_event}"), 400);
            }
        }
        return $data;
    }

    public static function Join($data, $kd_event, $alias = "mstevent", $type = "join")
    {
        $data = $data->{$type}(with(new MstEvent)->getTable() . " AS {$alias}", function ($q) use ($alias, $kd_event) {
            $q->on("{$alias}.kd_event", "=", $kd_event);
        });
        return $data;
    }
}
