<?php

namespace App\Http\Controllers\BE\MstData;

use App\Http\Controllers\BE\BaseController;
use App\Services\BaseService;
use App\Services\DB\MstBobotService;
use App\Services\DB\MstEventService;
use App\Services\DB\MstKriteriaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MstBobotController extends BaseController
{
    protected $pns = "Data Bobot";
    public function __construct()
    {
        parent::__construct();
    }

    public function Lists(Request $request)
    {
        try {
            $dtbobot = MstBobotService::Data();
            $dtbobot = $dtbobot->selectRaw("kd_event, COUNT(*) as ckriteria, SUM(bobot) AS bobot");
            $dtbobot = $dtbobot->groupBy("kd_event");

            $data = MstEventService::Data();
            $data = $data->leftJoinSub($dtbobot, "b", function ($q) {
                $q->on("b.kd_event", "=", "mstevent.kd_event");
            });
            $data = $data->select("mstevent.kd_event", "mstevent.nm_event");
            $data = $data->selectRaw("IFNULL(b.ckriteria, 0) AS ckriteria, CASE WHEN b.kd_event IS NOT NULL THEN 1 ELSE 0 END AS setup");
            $data = $data->orderBy("mstevent.nm_event")->get();
            $this->respon = BaseService::ResponseSuccess(BaseService::MsgSuccess($this->pns, 1), $data);
        } catch (\Throwable $th) {
            $this->respon = BaseService::ResponseError($th->getMessage(), $this->error, $th->getCode());
        }
        return $this->SendResponse();
    }

    public function DataBobot(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), ["kd_event" => "required"]);
            if ($validation->fails()) {
                $this->error = $validation->errors();
                throw new \Exception(BaseService::MessageCheckData(), 400);
            }
            $data = MstBobotService::Data($request->kd_event, true);
            $data = $data->select("mstbobot.kd_kriteria", "mk.nm_kriteria", "mstbobot.bobot");
            $data = $data->orderBy("mk.nm_kriteria")->get();
            $this->respon = BaseService::ResponseSuccess(BaseService::MsgSuccess($this->pns, 1), $data);
        } catch (\Throwable $th) {
            $this->respon = BaseService::ResponseError($th->getMessage(), $this->error, $th->getCode());
        }
        return $this->SendResponse();
    }

    public function KriteriaReady(Request $request)
    {
        try {
            $kd_event = !empty($request->kd_event) ? $request->kd_event : "";
            $data = MstKriteriaService::Data();
            $data = MstBobotService::Join($data, $kd_event, "mstkriteria.kd_kriteria", "b", "leftJoin", "v2");
            $data = $data->whereNull("b.kd_kriteria");
            if (isset($request->dtbobot) && count($request->dtbobot) > 0) {
                $dt = $request->dtbobot;
                $data = $data->where(function ($q) use ($dt) {
                    foreach ($dt as $val) {
                        $q->where("mstkriteria.kd_kriteria", "<>", $val);
                    }
                });
            }
            $data = $data->select("mstkriteria.kd_kriteria", "mstkriteria.nm_kriteria");
            $data = $data->orderBy("mstkriteria.nm_kriteria")->get();
            $this->respon = BaseService::ResponseSuccess(BaseService::MsgSuccess("Kriteria Ready", 1), $data);
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
                "kd_event" => "required",
                "dtbobot" => "required|array|min:1",
            ]);
            if ($validation->fails()) {
                $this->error = $validation->errors();
                throw new \Exception(BaseService::MessageCheckData(), 400);
            }

            // Check Event
            $dtjurusan = MstEventService::Detail($request->kd_event);
            // Check Jumlah Kriteria
            $dtkriteria = MstBobotService::Data($request->kd_event)->count();
            if ($dtkriteria > 0) {
                // Delete Data Kriteria
                MstBobotService::Data($request->kd_event)->delete();
            }

            // Insert Data Kriteria
            $total = 0;
            foreach ($request->dtbobot as $val) {
                // Check Data Kriteria
                $dtbobot = MstKriteriaService::Detail($val["kd_kriteria"]);
                $total += $val["bobot"];

                $data = MstBobotService::new ();
                $data->kd_event = $request->kd_event;
                $data->kd_kriteria = $val["kd_kriteria"];
                $data->bobot = $val["bobot"];
                $data->created_at = Carbon::now();
                $data->updated_at = Carbon::now();
                $data->save();
            }
            // Check Total Harus 100
            if ($total != 100) {
                throw new \Exception("Total Bobot tidak 100%!", 400);
            }
            $data = MstBobotService::Data($request->kd_event)->get();
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
            $data = MstBobotService::Data($request->kd_event);

            // Check Jumlah Kriteria
            $dtkriteria = $data->count();
            if ($dtkriteria > 0) {
                $data = $data->get();
                // Delete Data Kriteria
                MstBobotService::Data($request->kd_event)->delete();
            } else {
                throw new \Exception(BaseService::MessageNotFound("Setup Bobot"), 400);
            }
            DB::commit();
            $this->respon = BaseService::ResponseSuccess(BaseService::MsgSuccess($this->pns, 3), $data);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->respon = BaseService::ResponseError($th->getMessage(), $this->error, $th->getCode());
        }
        return $this->SendResponse();
    }
}
