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
                                    <th class="text-center align-middle" colspan="3">Data Peserta</th>
                                    <th class="text-center align-middle" rowspan="2">Status</th>
                                    <th class="disabled-sorting text-center align-middle" rowspan="2">Actions</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Username</th>
                                    <th class="text-center">Email</th>
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
                <x-form-group class="col-sm-12 col-md-12" label="Kode" name="kd_peserta" required />
                <x-form-group class="col-sm-12 col-md-12" label="Nama Peserta" name="nm_peserta" required />
                <x-form-group type="date" class="col-sm-12 col-md-12" label="Tanggal Lahir" name="tgl_lhr" required />
                <x-form-group class="col-sm-12 col-md-12" label="Alamat" name="alamat" required />
                <x-form-group class="col-sm-12 col-md-12" label="Email" name="email" required />
                <x-form-group class="col-sm-12 col-md-12" label="Username" name="username" required />
                <x-form-group class="col-sm-12 col-md-12" label="Password" name="password" required />
                <x-form-group type="select" class="col-sm-12 col-md-12" label="Jenis Kelamin" name="flag_active" required>
                    <option value="" disabled>--Choose Status--</option>
                    <option value="1">Laki-Laki</option>
                    <option value="0">Perempuan</option>
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
                        url: $apiUrl + "MasterData/Peserta/List"
                    }
                };
                table = PDataTables(dtu, [{
                    "data": null,
                    "className": "text-center",
                    "render": function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }, {
                    "data": "kd_peserta",
                }, {
                    "data": null,
                    "className": "text-center",
                    "render": function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }, {
                    "data": "nm_peserta",
                }, {
                    "data": "Tanggal lahir",
                    "className": "text-center",
                    "render": function(data, type, row, meta) {
                        return Convertyyyymmmddd(data);
                    }
                }, {
                    "data": "tgl_lhr",
                }, {
                    "data": null,
                    "className": "text-center",
                    "render": function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }, {
                    "data": "alamat",
                }, {
                    "data": null,
                    "className": "text-center",
                    "render": function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }, {
                    "data": "username",
                }, {
                    "data": null,
                    "className": "text-center",
                    "render": function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }, {
                    "data": "password",
                }, {
                    "data": "flag_active",
                    render: function(data, type, row, meta) {
                        return data == 1 ? "Laki-Laki" : "Perempuan";
                    }
                }, {
                    "data": null,
                    "orderable": false,
                    "className": "text-center",
                    render: function(data, type, row, meta) {
                        let html = "";
                        html += btnDataTable("Edit Peserta", "btn-outline-primary edit",
                            "fa fa-edit btn-outline-primary", true);
                        html += btnDataTable("Delete Peserta", "btn-outline-danger delete",
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
                        kd_event: data.kd_event,
                        nm_event: data.nm_event
                    };
                    $("#FDelData p").html("Are you sure to delete data Peserta <b>" + data.nm_event + "</b> ?");
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
            $("h4[labelAddEdit]").text(act + " Data Peserta");
            processData = {
                action: act,
                kd_peserta: (act == "Add" ? 0 : data.kd_peserta),
                nm_peserta: (act == "Add" ? "" : data.nm_peserta),
                jns_kel: (act == "Add" ? "" : data.jns_kel),
                tgl_lhr: (act == "Add" ? moment(new Date()).format("YYYY-MM-DD") : data.tgl_lhr),
                alamat: (act == "Add" ? "" : data.alamat),
                email: (act == "Add" ? "" : data.email),
                username: (act == "Add" ? "" : data.username),
                password: (act == "Add" ? "" : data.password),
                flag_active: (act == "Add" ? "" : data.flag_active),
            };
            $(form_id + " [name='kd_peserta'']").val(processData.kd_peserta).change();
            $(form_id + " [name='nm_peserta']").val(processData.nm_peserta).change();
            $(form_id + " [name='jns_kel']").val(processData.jns_kel).change();
            $(form_id + " [name='tgl_lhr']").val(processData.tgl_lhr).change();
            $(form_id + " [name='alamat']").val(processData.alamat).change();
            $(form_id + " [name='email']").val(processData.email).change();
            $(form_id + " [name='username']").val(processData.username).change();
            $(form_id + " [name='password']").val(processData.password).change();
            $(form_id + " [name='flag_active']").val(processData.flag_active).change();
            $(form_id).parsley().reset();
            ShowModal("MAddEditData");
        }

        Save = function() {
            let form_id = "#FAddEditData";
            if ($(form_id).parsley().validate()) {
                Loader("show");
                // Pass Data To Object
                processData.kd_peserta = $(form_id + " [name='kd_peserta']").val();
                processData.nm_peserta = $(form_id + " [name='nm_peserta']").val();
                processData.jns_kel = $(form_id + " [name='jns_kel']").val();
                processData.tgl_lhr = $(form_id + " [name='tgl_lhr']").val();
                processData.alamat = $(form_id + " [name='alamat']").val();
                processData.email = $(form_id + " [name='email']").val();
                processData.username = $(form_id + " [name='username']").val();
                processData.password = $(form_id + " [name='password']").val();
                processData.flag_active = $(form_id + " [name='flag_active']").val();
                //Setup Send Ajax
                let data = {
                    url: $apiUrl + "MasterData/Peserta/Save",
                    param: processData
                };
                // Process Ajax
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
                url: $apiUrl + "MasterData/Peserta/Delete",
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
            LoadPicker();
        });
    </script>
@endpush
