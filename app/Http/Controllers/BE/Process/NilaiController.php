<?php

namespace App\Http\Controllers\BE\Process;

use App\Http\Controllers\BE\BaseController;
use App\Services\BaseService;
use App\Services\DB\MstEventService;
use App\Services\DB\MstPesertaService;
use App\Services\DB\NilaiService;
use App\Services\DB\RegisterService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NilaiController extends BaseController
{
    protected $pns = "Data Nilai";
    public function __construct()
    {
        parent::__construct();
    }

    public function Lists(Request $request)
    {
        try {
            $data = [];
            if (!empty($request->kd_event)) {
                // Data Nilai
                $dtnilai = NilaiService::Data("", false);
                $dtnilai = $dtnilai->select("kd_event", "kd_peserta");
                $dtnilai = $dtnilai->selectRaw("SUM(nilai)/COUNT(nilai) AS rata");
                $dtnilai = $dtnilai->groupBy("kd_event", "kd_peserta");

                $data = RegisterService::Data($request->kd_event);
                $data = $data->leftJoinSub($dtnilai, "dn", function ($q) {
                    $q->on("dn.kd_event", "=", "registerevent.kd_event");
                    $q->on("dn.kd_peserta", "=", "registerevent.kd_peserta");
                });
                $data = $data->select("registerevent.kd_event", "registerevent.kd_peserta", "p.nm_peserta");
                $data = $data->selectRaw("IFNULL(dn.rata, 0) AS rata, CASE WHEN dn.kd_peserta IS NOT NULL THEN 1 ELSE 0 END AS setup");
                $data = $data->orderBy("p.nm_peserta")->get();
            }
            $this->respon = BaseService::ResponseSuccess(BaseService::MsgSuccess($this->pns, 1), $data);
        } catch (\Throwable $th) {
            $this->respon = BaseService::ResponseError($th->getMessage(), $this->error, $th->getCode());
        }
        return $this->SendResponse();
    }

    public function DataNilai(Request $request)
    {
        try {
            $data = [];
            if (!empty($request->kd_event)) {
                // Data Nilai
                $dtnilai = NilaiService::Data("", false);
                $dtnilai = $dtnilai->select("kd_event", "kd_peserta");
                $dtnilai = $dtnilai->selectRaw("SUM(nilai)/COUNT(nilai) AS rata");
                $dtnilai = $dtnilai->groupBy("kd_event", "kd_peserta");

                $data = RegisterService::Data($request->kd_event);
                $data = $data->leftJoinSub($dtnilai, "dn", function ($q) {
                    $q->on("dn.kd_event", "=", "registerevent.kd_event");
                    $q->on("dn.kd_peserta", "=", "registerevent.kd_peserta");
                });
                $data = $data->select("registerevent.kd_event", "registerevent.kd_peserta", "p.nm_peserta");
                $data = $data->selectRaw("IFNULL(dn.rata, 0) AS rata, CASE WHEN dn.kd_peserta IS NOT NULL THEN 1 ELSE 0 END AS setup");
                $data = $data->orderBy("p.nm_peserta")->get();
            }
            $this->respon = BaseService::ResponseSuccess(BaseService::MsgSuccess($this->pns, 1), $data);
        } catch (\Throwable $th) {
            $this->respon = BaseService::ResponseError($th->getMessage(), $this->error, $th->getCode());
        }
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

    public function PesertaReady(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), ["kd_event" => "required"]);
            if ($validation->fails()) {
                $this->error = $validation->errors();
                throw new \Exception(BaseService::MessageCheckData(), 400);
            }
            $data = MstPesertaService::Data();
            $data = RegisterService::Join($data, $request->kd_event, "mstpeserta.kd_peserta", "re", "leftJoin", "v2");
            $data = $data->whereNull("re.kd_peserta");
            $data = $data->select("mstpeserta.kd_peserta", "mstpeserta.nm_peserta");
            // dd($data->toSql());
            $data = $data->orderBy("mstpeserta.nm_peserta")->get();
            $this->respon = BaseService::ResponseSuccess(BaseService::MsgSuccess("Pserta Ready", 1), $data);
        } catch (\Throwable $th) {
            $this->respon = BaseService::ResponseError($th->getMessage(), $this->error, $th->getCode());
        }
        return $this->SendResponse();
    }
}
