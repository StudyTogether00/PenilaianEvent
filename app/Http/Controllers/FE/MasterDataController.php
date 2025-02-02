<?php

namespace App\Http\Controllers\FE;

use Illuminate\Http\Request;

class MasterDataController extends RouteController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function Event(Request $request)
    {
        $this->data["title"] = "Data Event";
        return view("pages.MasterData.Event", $this->data);
    }
    public function Kriteria(Request $request)
    {
        $this->data["title"] = "Data Kriteria";
        return view("pages.MasterData.Kriteria", $this->data);
    }
    public function Bobot(Request $request)
    {
        $this->data["title"] = "Data Bobot";
        return view("pages.MasterData.Bobot", $this->data);
    }
    public function Peserta(Request $request)
    {
        $this->data["title"] = "Data Peserta";
        return view("pages.MasterData.Peserta", $this->data);
    }
    public function Nilai(Request $request)
    {
        $this->data["title"] = "Data Nilai";
        return view("pages.MasterData.Nilai", $this->data);
    }
}
