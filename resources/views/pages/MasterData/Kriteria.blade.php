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
                                    <th colspan="10">
                                        <x-button type="button" class="btn-outline-success" icon="fa fa-plus"
                                            label="Add" onclick="Add()" />
                                        <x-button type="button" class="btn-outline-info" icon="fa fa-refresh"
                                            label="Refresh" onclick="Refresh()" />
                                    </th>
                                </tr>
                                <tr>
                                    <th class="text-center align-middle" rowspan="1">No</th>
                                    <th class="text-center align-middle" rowspan="2">Kriteria</th>
                                    <th class="text-center align-middle" rowspan="3">Tipe</th>
                                    <th class="disabled-sorting text-center align-middle" rowspan="2">Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Edit Modal --}}
    <x-modal-form id="AddEditData" title="labelAddEdit">
        <div class="modal-body">
            <div class="row">
                <x-form-group class="col-sm-12 col-md-12" label="Kode Kriteria" name="kd" required />
                <x-form-group class="col-sm-12 col-md-12" label="Kriteria" name="kriteria" required />
                <x-form-group type="select" class="col-sm-12 col-md-12" label="Tipe" name="flag_active" required>
                    <option value="" disabled>--Choose Status--</option>
                    <option value="1">Benefit</option>
                    <option value="0">Cost</option>
                </x-form-group>
            </div>
        </div>
        <div class="modal-footer">
            <x-button type="button" class="btn-outline-secondary mr-1" label="Close" data-dismiss="modal" />
            <x-button type="submit" class="btn-outline-primary" onclick="Save()">Save</x-button>
        </div>
    </x-modal-form>
    {{-- Delete Modal --}}
    <x-modal-form id="DelData">
        <div class="modal-body">
            <p></p>
        </div>
        <div class="modal-footer">
            <x-button type="button" class="btn-outline-secondary mr-1" label="Close" data-dismiss="modal" />
            <x-button type="submit" class="btn-outline-danger" onclick="Delete()">
                Delete
            </x-button>
        </div>
    </x-modal-form>
@endsection

@push('scripts')
    <script type="text/javascript">
        let table, id_tbl = "#datatables";
        let processData = {};

        Refresh = function() {
            if (!$.fn.DataTable.isDataTable(id_tbl)) {
                let dtu = {
                    id: id_tbl,
                    data: {
                        url: $apiUrl + "MasterData/Kriteria/List"
                    }
                };
                table = PDataTables(dtu, [{
                    "data": null,
                    "className": "text-center",
                    "render": function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }, {
                    "data": "kode",
                }, {
                    "data": "kriteria",
                }, {
                    "data": "flag_active",
                    "className": "text-right",
                    render: function(data, type, row, meta) {
                        return data == 1 ? "Benefit" : "Cost";
                    }               
                }, {
                    "data": null,
                    "orderable": false,
                    "className": "text-center",
                    render: function(data, type, row, meta) {
                        let html = "";
                        html += btnDataTable("Edit Kriteria", "btn-outline-primary edit",
                            "fa fa-edit btn-outline-primary", true);
                        html += btnDataTable("Delete Kriteria", "btn-outline-danger delete",
                            "fa fa-trash btn-outline-danger");
                        return html;
                    }
                }]);
                table.on('click', '.edit', function() {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    ShowData("Edit", data);
                });
                table.on('click', '.delete', function() {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    processData = {
                        kd: data.kd
                    };
                    $("#FDelData p").html("Are you sure to delete data kriteria <b>" + data
                        .kd +
                        "</b> ?");
                    ShowModal("MDelData");
                });
            } else {
                table.ajax.reload();
            }
        }

        Add = function() {
            ShowData();
        }

        ShowData = function(act = "Add", data = "") {
            let form_id = "#FAddEditData";
            $("h4[labelAddEdit]").text(act + " Data Kriteria");
            processData = {
                action: act,
                kd: (act == "Add" ? "" : data.kd),
                kriteria: (act == "Add" ? "" : data.kriteria),
                flag_active: (act == "Add" ? "" : data.flag_active),
            };
            $(form_id + " [name='kd']").val(processData.kd).change();
            $(form_id + " [name='kriteria']").val(processData.kriteria).change();
            $(form_id + " [name='flag_active']").val(processData.flag_active).change();
            $(form_id).parsley().reset();
            ShowModal("MAddEditData");
        }

        Save = function() {
            let form_id = "#FAddEditData";
            if ($(form_id).parsley().validate()) {
                Loader("show");
                // Pass Data To Object
                processData.kd = $(form_id + " [name='kd']").val();
                processData.kriteria = $(form_id + " [name='kriteria']").val();
                processData.flag_active = $(form_id + " [name='flag_active']").val();
                //Setup Send Ajax
                let data = {
                    url: $apiUrl + "MasterData/Kriteria/Save",
                    param: processData
                };
                SendAjax(data, function(result) {
                    MessageNotif(result.message, "success");
                    Refresh();
                    ShowModal("MAddEditData", "hide");
                }, function() {
                    Loader();
                });
            }
        }

        Delete = function() {
            Loader("show");
            let data = {
                url: $apiUrl + "MasterData/Kriteria/Delete",
                param: processData
            };
            SendAjax(data, function(result) {
                MessageNotif(result.message, "success");
                Refresh();
                ShowModal("MDelData", "hide");
            }, function() {
                Loader();
            });
        }

        $(document).ready(function() {
             Refresh();
        });
    </script>
@endpush
