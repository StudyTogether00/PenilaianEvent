<?php

namespace App\Http\Controllers\FE;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RouteController extends Controller
{
    protected $error = "";

    public function __construct()
    {
        parent::__construct();
    }

    public function Dashboard(Request $request)
    {
        $this->data["title"] = "Dashboard";
        return view("pages.dashboard", $this->data);
    }
    public function Login(Request $request)
    {
        $this->data["title"] = "Login";
        return view("pages.Login", $this->data);
    }

    public function CheckSession(Request $request)
    {
        $token = !empty($request->session()->get("data.token")) ? $request->session()->get("data.token") : "";
        if (empty($token)) {
            return false;
        }
        return true;
    }

    public function SignIn(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                "username" => "required",
                "password" => "required",
            ]);
            if ($validation->fails()) {
                $this->error = $validation->errors();
                throw new \Exception(BaseService::MessageCheckData(), 400);
            }

            $dat = [];

            // Check Login
            $dtadmin = User::where([
                "username" => $request->username,
                "password" => md5($request->password),
                "flag_active" => 1,
            ])->first();
            if (empty($dtadmin->userid)) {
                /*$datauser = SiswaService::Data()->where([
                "nisn" => $request->username,
                "nisn" => $request->password,
                ])->first();
                if (empty($datauser->nisn)) {*/
                throw new \Exception("Username and Password is incorrect !", 400);
                /*}
            $data = [
            "userid" => $datauser->nisn,
            "username" => $datauser->nisn,
            "fullname" => $datauser->nama_siswa,
            "role" => "user",
            ];*/
            } else {
                $data = [
                    "userid" => $dtadmin->userid,
                    "username" => $dtadmin->username,
                    "fullname" => $dtadmin->fullname,
                    "role" => "admin",
                ];
            }
            $dat["token"] = "login";
            $dat["data"] = $data;
            $request->session()->put('data', $dat);

            $this->respon = BaseService::ResponseSuccess("Success Get Data Token", $data);
        } catch (\Throwable $th) {
            $this->respon = BaseService::ResponseError($th->getMessage(), $this->error, $th->getCode());
        }
        return $this->SendResponse();
    }
    public function DestroySession(Request $request)
    {
        try {
            $request->session()->invalidate();
            $data = [];
            $this->respon = BaseService::ResponseSuccess("Success Destroy Session", $data);
        } catch (\Throwable $th) {
            $this->respon = BaseService::ResponseError($th->getMessage(), $this->error, $th->getCode());
        }
        return $this->SendResponse();
    }
}
