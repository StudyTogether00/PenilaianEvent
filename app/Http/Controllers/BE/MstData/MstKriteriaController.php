<?php

namespace App\Http\Controllers\BE\MstData;

use App\Http\Controllers\BE\BaseController;
use App\Services\BaseService;
use App\Services\DB\MstKriteriaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MstKriteriaController extends BaseController
{
    protected $pns = "Data Kriteria";
    public function __construct()
    {
        parent::__construct();
    }

    public function Lists(Request $request)
    {
        try {
            $data = MstKriteriaService::Data(null);
            $data = $data->select("kd_kriteria", "nm_kriteria", "tipe", "flag_active");
            $data = $data->orderBy("nm_kriteria")->get();
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
                "kd_kriteria" => "required_if:action,Edit|nullable",
                "nm_kriteria" => "required",
                "tipe" => "required|integer",
                "flag_active" => "required|boolean",
            ]);
            if ($validation->fails()) {
                $this->error = $validation->errors();
                throw new \Exception(BaseService::MessageCheckData(), 400);
            }
            if ($request->action == "Add") {
                $request->kd_kriteria = 0;
            }

            // Check Nama Kriteria
            $cek = MstKriteriaService::Data()->where("nm_kriteria", $request->nm_kriteria)->where("kd_kriteria", "<>", $request->kd_kriteria)->count();
            if ($cek > 0) {
                throw new \Exception(BaseService::MessageDataExists("nm_kriteria {$request->nm_kriteria}"), 400);
            }

            // Save Or Update
            $data = MstKriteriaService::Detail($request->kd_kriteria, null, $request->action);
            if ($request->action == "Add") {
                $data = MstKriteriaService::new ();
                $data->created_at = Carbon::now();
            }
            $data->nm_kriteria = $request->nm_kriteria;
            $data->tipe = $request->tipe;
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
            $validation = Validator::make($request->all(), ["kd_kriteria" => "required"]);
            if ($validation->fails()) {
                $this->error = $validation->errors();
                throw new \Exception(BaseService::MessageCheckData(), 400);
            }

            // Delete
            $data = MstKriteriaService::Detail($request->kd_kriteria, null);
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
