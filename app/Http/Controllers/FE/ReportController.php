<?php

namespace App\Http\Controllers\FE;

use Illuminate\Http\Request;

class ReportController extends RouteController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function Normalisasi(Request $request)
    {
        $this->data["title"] = "Normalisasi";
        return view("pages.Report.Normalisasi", $this->data);
    }
}
