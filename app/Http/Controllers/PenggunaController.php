<?php

namespace App\Http\Controllers;

use App\Models\DbPengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenggunaController extends Controller
{
    public function lists(request $request)
    
    {
        try {
            $data = DbPengguna::select("kd","nm_pengguna")->get();
            $this->ResponSuccess("List Data Pengguna", $data);

        }catch (\Throwable $th){
            $this->ResponError($th->getMessage(),"",$th->getCode());
        }

        return $this->SendRespon();
    
        return response()->json($this->respon,$code);
    
    }

    public function Save(request $request)

    {
        try {
            $validation = Validator::make($request->all(), [
                "action" => "required|in:Add,Edit",
                "kd" => "required_if:action,Edit",
                "nm_pengguna" => "required",
            
            ]);
            if ($validation->fails()){
                $this->error = $validation->errors();
                throw new \Exception("please cek data", 400);
            }

            if ($request->action =>"Add"){
                
            }


            $data = $request->all();
            $this->ResponSuccess("List Data Pengguna", $data);

        }catch (\Throwable $th){
            $this->ResponError($th->getMessage(),$this->error,$th->getCode());
        }

        return $this->SendRespon();

    }
    public function Delete(request $request)
{
    try {
        $data = DbPengguna::select("kd","nm_pengguna")->get();
        $this->ResponSuccess("List Data Pengguna", $data);

    }catch (\Throwable $th){
        $this->ResponError($th->getMessage(),"",$th->getCode());
    }

    return $this->SendRespon();
}
    public function index(Request$request)

{
    $data = DbPengguna::all();
    return response()->json(["data" => $data]);
}

public function ResponSuccess($message = "",$data = "")
{
    $this->respon =[
        "code" => 200,
        "content" => [
            "status" => true,
            "message" => $message,
            "data" => $data,
        ],
    ];
}

public function ResponError($message = "",$data = "",$code = 200)
{
    $this->respon =[
        "code" => $code,
        "content" => [
            "status" => false,
            "message" => $message,
            "data" => $data,
        ],
    ];
}
public function SendRespon()
{
    return response()->json($this->respon["content"], $this->respon["code"]);
}

}
