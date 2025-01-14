<?php

namespace App\Http\Controllers\FE;

use Illuminate\Http\Request;

class ProcessController extends RouteController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function Register(Request $request)
    {
        $this->data["title"] = "Register Peserta";
        return view("pages.Process.Register", $this->data);
    }
    public function Nilai(Request $request)
    {
        $this->data["title"] = "Input Data Nilai";
        return view("pages.Process.Nilai", $this->data);
    }
}
