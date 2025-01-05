<?php

namespace App\Http\Controllers\BE\MstData;

use App\Http\Controllers\BE\BaseController;
use App\Services\BaseService;
use App\Services\DB\KriteriaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KriteriaController extends BaseController
{
    protected $pns = "Data Kriteria";
    public function __construct()
    {
        parent::__construct();
    }

    public function Lists(Request $request)
    {
        try {
            $data = KriteriaService::Data();
            $data = $data->select("kd", "kriteria","tipe");
            $data = $data->orderBy("kriteria")->get();

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
                "kd" => "required_if:action,Edit|nullable",
                "kriteria" => "required",
                "tipe" => "required",
            ]);
            if ($validation->fails()) {
                $this->error = $validation->errors();
                throw new \Exception(BaseService::MessageCheckData(), 400);
            }
            if ($request->action == "Add") {
                $request->kd = "";
            }

            // Check Nama Jurasan
            $cek = KriteriaService::Data()->where("kriteria", $request->kriteria)->where("kd", "<>", $request->kd)->count();
            if ($cek > 0) {
                throw new \Exception(BaseService::MessageDataExists("kriteria {$request->kriteria}"), 400);
            }
            // Save Or Update
            $data = KriteriaService::Detail($request->kd, $request->action);
            if ($request->action == "Add") {
                $data = KriteriaService::new ();
                $last = KriteriaService::Data()->where(DB::raw("LEFT(kd, 1)"), "J")
                ->where(DB::raw("LENGTH(kd)"),"10")->orderBy("kd", "desc")->first();
                $kd = "J" . substr("00000000" . (intval(empty($last->kd) ? 0 : substr($last->kd, -9)) + 1), -9);
                $data->kd = $kd;
                $data->created_at = Carbon::now();
            }
            $data->kriteria = $request->kriteria;
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
            $validation = Validator::make($request->all(), ["kd" => "required"]);
            if ($validation->fails()) {
                $this->error = $validation->errors();
                throw new \Exception(BaseService::MessageCheckData(), 400);
            }

            // Delete
            $data = KriteriaService::Detail($request->kd);
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