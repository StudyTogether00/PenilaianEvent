<?php

namespace App\Http\Controllers\BE\Process;

use App\Http\Controllers\BE\BaseController;
use App\Services\BaseService;
use App\Services\DB\MstEventService;
use App\Services\DB\MstPesertaService;
use App\Services\DB\RegisterService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BaseController
{
    protected $pns = "Data Register";
    public function __construct()
    {
        parent::__construct();
    }

    public function Lists(Request $request)
    {
        try {
            $data = [];
            if (!empty($request->kd_event)) {
                $data = RegisterService::Data($request->kd_event);
                $data = $data->select(
                    "registerevent.kd_event", "e.nm_event", "registerevent.kd_peserta", "p.nm_peserta",
                    "registerevent.no_event", "registerevent.tgl_register", "p.jns_kel", "p.email", "p.tgl_lhr"
                );
                $data = $data->orderBy("p.nm_peserta")->get();
            }

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
                "kd_event" => "required",
                "kd_peserta" => "required",
                "tgl_register" => "required|date",
                "no_event" => "required",
            ]);
            if ($validation->fails()) {
                $this->error = $validation->errors();
                throw new \Exception(BaseService::MessageCheckData(), 400);
            }

            // Check Data event
            $dtevent = MstEventService::Detail($request->kd_event);
            // Check Data Peserta
            $dtpeserta = MstPesertaService::Detail($request->kd_peserta);

            // Save Or Update
            $data = RegisterService::Detail($request->kd_event, $request->kd_peserta, $request->action);
            if ($request->action == "Add") {
                $data = RegisterService::new ();
                $data->kd_event = $request->kd_event;
                $data->kd_peserta = $request->kd_peserta;
                $data->created_at = Carbon::now();
            }
            $data->no_event = $request->no_event;
            $data->tgl_register = $request->tgl_register;
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
            $validation = Validator::make($request->all(), ["kd_event" => "required", "kd_peserta" => "required"]);
            if ($validation->fails()) {
                $this->error = $validation->errors();
                throw new \Exception(BaseService::MessageCheckData(), 400);
            }

            // Delete
            $data = RegisterService::Detail($request->kd_event, $request->kd_peserta);
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
