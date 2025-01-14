<?php

namespace App\Http\Controllers\BE\Process;

use App\Http\Controllers\BE\BaseController;
use App\Services\BaseService;
use App\Services\DB\MstPesertaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BaseController
{
    protected $pns = "Data Master Peserta";
    public function __construct()
    {
        parent::__construct();
    }

    public function Lists(Request $request)
    {
        try {
            $data = MstPesertaService::Data(null);
            $data = $data->select(
                "kd_peserta", "nm_peserta", "jns_kel", "tgl_lhr", "alamat",
                "email", "username", "password", "flag_active"
            );
            $data = $data->orderBy("nm_peserta")->get();

            $this->respon = BaseService::ResponseSuccess(BaseService::MsgSuccess($this->pns, 1), $data);
        } catch (\Throwable $th) {
            $this->respon = BaseService::ResponseError($th->getMessage(), $this->error, $th->getCode());
        }
        return $this->SendResponse();
    }

    public function Save(Request $request)
    {
        try {
            DB::beginTransaction();
            $validation = Validator::make($request->all(), [
                "action" => "required|in:{$this->option_action}",
                "kd_peserta" => "required_if:action,Edit|nullable",
                "nm_peserta" => "required",
                "jns_kel" => "required|boolean",
                "tgl_lhr" => "required|date",
                "alamat" => "required",
                "email" => "required",
                "username" => "required",
                "password" => "required",
                "flag_active" => "required|boolean",
            ]);
            if ($validation->fails()) {
                $this->error = $validation->errors();
                throw new \Exception(BaseService::MessageCheckData(), 400);
            }
            if ($request->action == "Add") {
                $request->kd_peserta = 0;
            }

            // Check Data Username
            $cek = MstPesertaService::Data(null)->where("username", $request->username)->where("kd_peserta", "<>", $request->kd_peserta)->count();
            if ($cek > 0) {
                throw new \Exception(BaseService::MessageDataExists("Username {$request->username}"), 400);
            }

            // Save Or Update
            $data = MstPesertaService::Detail($request->kd_peserta, null, $request->action);
            if ($request->action == "Add") {
                $data = MstPesertaService::new ();
                $data->created_at = Carbon::now();
                $data->username = $request->username;
            }
            $data->nm_peserta = $request->nm_peserta;
            $data->jns_kel = $request->jns_kel;
            $data->tgl_lhr = $request->tgl_lhr;
            $data->alamat = $request->alamat;
            $data->email = $request->email;
            if ($data->password != $request->password && !empty($request->password)) {
                $data->password = md5($request->password);
            }
            $data->flag_active = $request->flag_active;
            $data->updated_at = Carbon::now();
            $data->save();

            DB::commit();
            $this->respon = BaseService::ResponseSuccess(BaseService::MsgSuccess($this->pns, 2), $data);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->respon = BaseService::ResponseError($th->getMessage(), $this->error, $th->getCode());
        }
        return $this->SendResponse();
    }

    public function Delete(Request $request)
    {
        try {
            DB::beginTransaction();
            $validation = Validator::make($request->all(), ["kd_peserta" => "required"]);
            if ($validation->fails()) {
                $this->error = $validation->errors();
                throw new \Exception(BaseService::MessageCheckData(), 400);
            }

            // Delete
            $data = MstPesertaService::Detail($request->kd_peserta, null);
            $data->delete();

            DB::commit();
            $this->respon = BaseService::ResponseSuccess(BaseService::MsgSuccess($this->pns, 3), $data);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->respon = BaseService::ResponseError($th->getMessage(), $this->error, $th->getCode());
        }
        return $this->SendResponse();
    }
}
