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
                                    <th class="text-center align-middle" rowspan="2">No</th>
                                    <th class="text-center align-middle" colspan="4">Event</th>
                                    <th class="text-center align-middle" colspan="2">Register</th>
                                    <th class="text-center align-middle" rowspan="2">Status</th>
                                    <th class="disabled-sorting text-center align-middle" rowspan="2">Actions</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Mulai</th>
                                    <th class="text-center">Selesai</th>
                                    <th class="text-center">Kouta</th>
                                    <th class="text-center">Mulai</th>
                                    <th class="text-center">Selesai</th>
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
                <x-form-group class="col-sm-12 col-md-12" label="Kode Mata Pelajaran" name="kd_matapelajaran" required />
                <x-form-group class="col-sm-12 col-md-12" label="Nama Mata Pelajaran" name="nama_matapelajaran" required />
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
                        url: $apiUrl + "MasterData/Mapel/List"
                    }
                };
                table = PDataTables(dtu, [{
                    "data": null,
                    "className": "text-center",
                    "render": function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }, {
                    "data": "kd_matapelajaran",
                }, {
                    "data": "nama_matapelajaran",
                }, {
                    "data": null,
                    "orderable": false,
                    "className": "text-center",
                    render: function(data, type, row, meta) {
                        let html = "";
                        html += btnDataTable("Edit Mapel", "btn-outline-primary edit",
                            "fa fa-edit btn-outline-primary", true);
                        html += btnDataTable("Delete Mapel", "btn-outline-danger delete",
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
                        kd_matapelajaran: data.kd_matapelajaran
                    };
                    $("#FDelData p").html("Are you sure to delete data code Mata Pelajaran <b>" + data
                        .kd_matapelajaran +
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
            $("h4[labelAddEdit]").text(act + " Data Mata Pelajaran");
            processData = {
                action: act,
                kd_matapelajaran: (act == "Add" ? "" : data.kd_matapelajaran),
                nama_matapelajaran: (act == "Add" ? "" : data.nama_matapelajaran)
            };
            act == "Add" ? $(form_id + " [name='kd_matapelajaran']").removeAttr('disabled') : $(form_id +
                " [name='kd_matapelajaran']").attr('disabled', true);
            $(form_id + " [name='kd_matapelajaran']").val(processData.kd_matapelajaran).change();
            $(form_id + " [name='nama_matapelajaran']").val(processData.nama_matapelajaran).change();

            $(form_id).parsley().reset();
            ShowModal("MAddEditData");
        }

        function Save() {
            let form_id = "#FAddEditData";
            if ($(form_id).parsley().validate()) {
                Loader("show");
                processData.kd_matapelajaran = $(form_id + " [name='kd_matapelajaran']").val();
                processData.nama_matapelajaran = $(form_id + " [name='nama_matapelajaran']").val();
                let data = {
                    url: $apiUrl + "MasterData/Mapel/Save",
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
                url: $apiUrl + "MasterData/Mapel/Delete",
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
            // Refresh();
        });
    </script>
@endpush
