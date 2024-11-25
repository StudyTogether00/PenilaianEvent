<?php

namespace App\Http\Controllers\FE;

use Illuminate\Http\Request;

class MasterDataController extends RouteController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function Jurusan(Request $request)
    {
        $this->data["title"] = "Data Jurusan";
        return view("pages.MasterData.Jurusan", $this->data);
    }
    public function Siswa(Request $request)
    {
        $this->data["title"] = "Data Siswa";
        return view("pages.MasterData.Siswa", $this->data);
    }
    public function MataPelajaran(Request $request)
    {
        $this->data["title"] = "Data Mata Pelajaran";
        return view("pages.MasterData.MataPelajaran", $this->data);
    }
    public function Bobot(Request $request)
    {
        $this->data["title"] = "Data Bobot";
        return view("pages.MasterData.Bobot", $this->data);
    }
}
