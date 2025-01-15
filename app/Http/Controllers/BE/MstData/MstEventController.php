<?php

namespace App\Http\Controllers\BE\MstData;

use App\Http\Controllers\BE\BaseController;
use App\Services\BaseService;
use App\Services\DB\MstEventService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MstEventController extends BaseController
{
    protected $pns = "Data Master Event";
    public function __construct()
    {
        parent::__construct();
    }

    public function Lists(Request $request)
    {
        try {
            $data = MstEventService::Data(null);
            $data = $data->select("kd_event", "nm_event", "tgl_event", "kuota", "flag_active");
            $data = $data->orderBy("nm_event")->get();

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
                "kd_event" => "required_if:action,Edit|nullable",
                "nm_event" => "required",
                "tgl_event" => "required|date",
                "kuota" => "required|integer",
                "flag_active" => "required|boolean",
            ]);
            if ($validation->fails()) {
                $this->error = $validation->errors();
                throw new \Exception(BaseService::MessageCheckData(), 400);
            }
            if ($request->action == "Add") {
                $request->kd_event = 0;
            }

            // Check Nama Event
            $cek = MstEventService::Data(null)->where("nm_event", $request->nm_event)->where("kd_event", "<>", $request->kd_event)->count();
            if ($cek > 0) {
                throw new \Exception(BaseService::MessageDataExists("Nama {$request->nm_event}"), 400);
            }

            // Save Or Update
            $data = MstEventService::Detail($request->kd_event, null, $request->action);
            if ($request->action == "Add") {
                $data = MstEventService::new ();
                $data->created_at = Carbon::now();
            }
            $data->nm_event = $request->nm_event;
            $data->tgl_event = $request->tgl_event;
            $data->kuota = $request->kuota;
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
            $validation = Validator::make($request->all(), ["kd_event" => "required"]);
            if ($validation->fails()) {
                $this->error = $validation->errors();
                throw new \Exception(BaseService::MessageCheckData(), 400);
            }

            // Delete
            $data = MstEventService::Detail($request->kd_event, null);
            $data->delete();

            DB::commit();
            $this->respon = BaseService::ResponseSuccess(BaseService::MsgSuccess($this->pns, 3), $data);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->respon = BaseService::ResponseError($th->getMessage(), $this->error, $th->getCode());
        }
        return $this->SendResponse();
    }

    public function Active(Request $request)
    {
        try {
            $data = MstEventService::Data();
            $data = $data->select("kd_event", "nm_event");
            $data = $data->orderBy("nm_event")->get();

            $this->respon = BaseService::ResponseSuccess(BaseService::MsgSuccess("{$this->pns} Active", 1), $data);
        } catch (\Throwable $th) {
            $this->respon = BaseService::ResponseError($th->getMessage(), $this->error, $th->getCode());
        }
        return $this->SendResponse();
    }
}
