<?php

namespace App\Http\Controllers\BE\Process;

use App\Http\Controllers\BE\BaseController;
use App\Services\BaseService;
use App\Services\DB\MstBobotService;
use App\Services\DB\MstEventService;
use App\Services\DB\MstKriteriaService;
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
            $validation = Validator::make($request->all(), ["kd_event" => "required", "kd_peserta" => "required"]);
            if ($validation->fails()) {
                $this->error = $validation->errors();
                throw new \Exception(BaseService::MessageCheckData(), 400);
            }
            $data = MstBobotService::Data($request->kd_event, true);
            $data = NilaiService::Join($data, "mstbobot.kd_event", "mstbobot.kd_kriteria", $request->kd_peserta, "nd", "leftJoin", "v2");
            $data = $data->select("mstbobot.kd_event", "mstbobot.kd_kriteria", "mk.nm_kriteria");
            $data = $data->selectRaw("IFNULL(nd.nilai, 0) AS nilai");
            $data = $data->orderBy("mk.nm_kriteria")->get();
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
                "kd_event" => "required",
                "kd_peserta" => "required",
                "dtnilai" => "required|array|min:1",
            ]);
            if ($validation->fails()) {
                $this->error = $validation->errors();
                throw new \Exception(BaseService::MessageCheckData(), 400);
            }

            // Check Data Event
            $dtevent = MstEventService::Detail($request->kd_event);
            // Check Data Peserta
            $dtpeserta = MstPesertaService::Detail($request->kd_peserta);
            // Check Data Register
            $regis = RegisterService::Detail($request->kd_event, $request->kd_peserta);

            // Delete Data Detail Nilai
            NilaiService::Data($request->kd_event, false)->where("nilaidetail.kd_peserta", $request->kd_peserta)->delete();

            // Save Or Update
            $timeupdate = Carbon::now();
            $totalNilai = 0;
            $countNilai = 0;
            foreach ($request->dtnilai as $val) {
                // Check Kriteria
                $dtnilai = MstKriteriaService::Detail($val["kd_kriteria"]);
                // Check Kriteria in Bobot
                $dtbobot = MstBobotService::Detail($request->kd_event, $val["kd_kriteria"]);

                $data = NilaiService::new ();
                $data->kd_event = $request->kd_event;
                $data->kd_peserta = $request->kd_peserta;
                $data->kd_kriteria = $val["kd_kriteria"];
                $data->nilai = $val["nilai"];
                $data->created_at = $timeupdate;
                $data->updated_at = $timeupdate;
                $data->save();

                $totalNilai += $val["nilai"];
                $countNilai++;
            }
            $data = NilaiService::Data($request->kd_event, false)->where("nilaidetail.kd_peserta", $request->kd_peserta)->get();
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

            // Check data
            $dt = NilaiService::Data($request->kd_event, false)->where("nilaidetail.kd_peserta", $request->kd_peserta)->count();
            if ($dt > 0) {
                $data = NilaiService::Data($request->kd_event, false)->where("nilaidetail.kd_peserta", $request->kd_peserta);
                $data->delete();
            } else {
                throw new \Exception(BaseService::MessageNotFound(), 400);
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
