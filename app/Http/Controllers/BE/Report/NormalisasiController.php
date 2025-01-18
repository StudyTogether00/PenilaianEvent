<?php

namespace App\Http\Controllers\BE\Report;

use App\Http\Controllers\BE\BaseController;
use App\Services\BaseService;
use App\Services\DB\MstPesertaService;
use App\Services\DB\NilaiService;
use App\Services\DB\RegisterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NormalisasiController extends BaseController
{
    protected $pns = "Data Normalisasi";
    public function __construct()
    {
        parent::__construct();
    }

    public function DataKeputusan(Request $request)
    {
        try {
            $data = [];
            $kd_event = $request->kd_event;
            if (!empty($kd_event)) {
                // Data Nilai
                $nilai = NilaiService::Data("", true);
                $nilai = $nilai->select("nilaidetail.kd_event", "nilaidetail.kd_peserta");
                $nilai = $nilai->selectRaw("SUM(nilaidetail.nilai * b.bobot / 100) AS nilai");
                $nilai = $nilai->groupBy("nilaidetail.kd_event", "nilaidetail.kd_peserta");

                $data = RegisterService::Data($request->kd_event);
                $data = $data->leftJoinSub($nilai, "dn", function ($q) {
                    $q->on('dn.kd_event', '=', 'registerevent.kd_event');
                    $q->on('dn.kd_peserta', '=', 'registerevent.kd_peserta');
                });
                $data = $data->select(
                    "registerevent.kd_event", "e.nm_event",
                    "registerevent.kd_peserta", "p.nm_peserta",
                    "registerevent.no_event", "registerevent.tgl_register"
                );
                $data = $data->selectRaw("IFNULL(dn.nilai, 0) AS nilai, CASE WHEN dn.kd_peserta IS NOT NULL THEN 1 ELSE 0 END AS setup");
                $data = $data->orderBy("dn.nilai", "DESC")->orderBy("registerevent.no_event")->get();
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
            // data matrix min and max nilai per Event and Kriteria
            $dtmatrix = NilaiService::Data("", false);
            $dtmatrix = $dtmatrix->select("kd_event", "kd_kriteria");
            $dtmatrix = $dtmatrix->selectRaw("MIN(nilai) AS minnilai, MAX(nilai) AS maxnilai");
            $dtmatrix = $dtmatrix->groupBy("kd_event", "kd_kriteria");

            $data = NilaiService::Data($request->kd_event, true);
            $data = MstPesertaService::Join($data, "nilaidetail.kd_peserta", "p");
            $data = $data->leftJoinSub($dtmatrix, "mt", function ($q) {
                $q->on("mt.kd_event", "=", "nilaidetail.kd_event");
                $q->on("mt.kd_kriteria", "=", "nilaidetail.kd_kriteria");
            });
            $data = $data->where("nilaidetail.kd_peserta", $request->kd_peserta);
            $data = $data->select(
                "nilaidetail.kd_event",
                "nilaidetail.kd_kriteria", "k.nm_kriteria", "k.tipe",
                "nilaidetail.kd_peserta", "p.nm_peserta",
                "nilaidetail.nilai", "b.bobot"
            );
            $data = $data->selectRaw("IFNULL(mt.minnilai, 0) AS minnilai, IFNULL(mt.maxnilai, 0) AS maxnilai");
            $data = $data->orderBy("k.nm_kriteria")->get();
            $this->respon = BaseService::ResponseSuccess(BaseService::MsgSuccess($this->pns, 1), $data);
        } catch (\Throwable $th) {
            $this->respon = BaseService::ResponseError($th->getMessage(), $this->error, $th->getCode());
        }
        return $this->SendResponse();
    }

    public function DownloadReport(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), ["tahun" => "required"]);
            if ($validation->fails()) {
                $this->error = $validation->errors();
                throw new \Exception(BaseService::MessageCheckData(), 400);
            }
            $data = [];
            if (!empty($request->tahun)) {
                $data = [];
            }

            $this->respon = BaseService::ResponseSuccess(BaseService::MsgSuccess($this->pns, 1), $data);
        } catch (\Throwable $th) {
            $this->respon = BaseService::ResponseError($th->getMessage(), $this->error, $th->getCode());
        }
        return $this->SendResponse();
    }
}
