@extends('layout.Layout')
@section('content')
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="material-datatables">
                        <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0"
                            width="100%" style="width:100%">
                            <thead>
                                <tr>
                                    <th colspan="6">
                                        <x-button type="button" class="btn-outline-info" icon="fa fa-refresh"
                                            label="Refresh" onclick="Refresh()" />
                                    </th>
                                </tr>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama Peserta</th>
                                    <th class="text-center">Tanggal Lahir</th>
                                    <th class="text-center">Nilai</th>
                                    <th class="text-center">Ranking</th>
                                    <th class="disabled-sorting text-center">Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Add Edit Modal --}}
    <x-modal-form id="AddEditData" title="labelAddEdit" class="modal-lg">
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-6">
                    <x-form-group type="select" class="col-sm-12 col-md-12" label="Jurusan" name="kd_jurusan"
                        onchange="LoadNilai()">
                        <option value="" disabled>--Choose Jurusan--</option>
                    </x-form-group>
                </div>
                <div class="material-datatables col-sm-12">
                    <table id="tableMaple" class="table table-striped table-no-bordered table-hover" cellspacing="0"
                        width="100%" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kriteria</th>
                                <th>Bobot</th>
                                <th>Nilai</th>
                                <th>Matrix</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-left">Nilai Akhir</th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </x-modal-form>
@endsection