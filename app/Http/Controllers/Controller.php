<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $respon = [];
    protected $data = [];
    public function __construct()
    {}

    // Respon Config
    public function ResponCode($code)
    {
        return is_numeric($code) ? ($code >= 600 ? 500 : $code) : 500;
    }
    // Send Response
    public function SendResponse()
    {
        $data = empty($this->respon["data"]) || !isset($this->respon["data"]) ? [] : $this->respon["data"];
        $this->respon["status"] = ($this->respon["status"] === 0) ? 400 : $this->respon["status"];
        $status = empty($this->respon["status"]) || !isset($this->respon["status"]) ? 200 : $this->ResponCode($this->respon["status"]);
        return response()->json($data, $status);
    }
}
