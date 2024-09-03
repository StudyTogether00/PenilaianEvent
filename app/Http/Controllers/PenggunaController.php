<?php

namespace App\Http\Controllers;

use App\Models\DbPengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class PenggunaController extends Controller
{
    public function lists(request $request)
    
    {
        try {
            $data = DbPengguna::select("kd","nm_pengguna")->get();
            $respon = [
            "status" => true,
            "message" => "List Data Pengguna",
            "data" => $data,
            ];
            $code = 200;
        }catch (\Throwable $th){
            $respon = [
            "status" => false,
            "message" => $th->getMessage(),
            "data" => [],
            ];
            $code = 400;
                
        }
    
        return response()->json($respon,$code);
    
    }

    public function Add(request $request)

    {


    }
    public function Delete(request $request)
{

}
    public function index(Request$request)

{
    $data = DbPengguna::all();
    return response()->json(["data" => $data]);
}


}
