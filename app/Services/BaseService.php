<?php

namespace App\Services;

class BaseService
{
    public static function ResponseSuccess($message = "", $data = "")
    {
        return self::ResponseJson($message, $data, true, 200);
    }
    public static function ResponseError($message = "", $data = "", $status = 200)
    {
        return self::ResponseJson($message, $data, false, $status);
    }
    public static function ResponseJson($message = "", $data = "", $stt = true, $status = 200)
    {
        return [
            "data" => [
                "status" => $stt,
                "message" => $message,
                "data" => $data,
            ],
            "status" => $status,
        ];
    }

    public static function MsgSuccess($data, $type = 1)
    {
        $data = !empty($data) ? " {$data}" : $data;
        $dt = "Success";
        if ($type === 1) {
            $dt .= " Get List";
        } elseif ($type === 2) {
            $dt .= " Saving";
        } elseif ($type === 3) {
            $dt .= " Deleting";
        } elseif ($type === 4) {
            $dt .= " Sychronize";
        }
        return "{$dt}{$data}";
    }
    public static function MessageCheckData()
    {
        return "Please, Check Data !";
    }
    public static function MessageDataExists($data = "")
    {
        $data = !empty($data) ? " {$data}" : $data;
        return "Data{$data} Already Exits!";
    }
    public static function MessageNotFound($data = "")
    {
        $data = !empty($data) ? " {$data}" : $data;
        return "Data{$data} Not Found!";
    }
}
