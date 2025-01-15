@extends('layout.Layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <x-form-group type="select" class="col-sm-12 col-md-6" label="Event" name="kd_event"
                                onchange="Refresh()">
                                <option value="" disabled>--Choose Event--</option>
                            </x-form-group>
                        </div>
                        <div class="material-datatables col-sm-12">
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
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Nilai Rata2</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Edit Modal --}}
    <x-modal-form id="AddEditData" title="labelAddEdit">
        <div class="modal-body">
            <div class="row">
                <x-form-group class="col-sm-12 col-md-12" label="No Register" name="no_event" required />
                <x-form-group type="date" class="col-sm-12 col-md-12" label="Tanggal Register" name="tgl_register"
                    required />
                <x-form-group type="select" class="col-sm-12 col-md-12" label="Peserta" name="kd_peserta" required>
                    <option value="" disabled>--Choose Peserta--</option>
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
        let kd_event = "";

        DataEvent = function() {
            SendAjax({
                url: $apiUrl + "MasterData/Event/Active",
            }, function(result) {
                let html = "";
                $.each(result.data, function(index, value) {
                    html += '<option value="' + value.kd_event + '">' + value.nm_event +
                        '</option>';
                });
                if (html != "") {
                    $(html).insertAfter("[name='kd_event'] option:first");
                    $(".selectpicker").selectpicker('refresh');
                }
                $("[name='kd_event']").val("").change();
            });
        }

        Refresh = function() {
            kd_event = $("[name='kd_event']").val();
            if (!$.fn.DataTable.isDataTable(id_tbl)) {
                let dtu = {
                    id: id_tbl,
                    data: {
                        url: $apiUrl + "Process/Register/List",
                        param: function() {
                            var d = {};
                            d.kd_event = kd_event;
                            return JSON.stringify(d);
                        }
                    }
                };
                table = PDataTables(dtu, [{
                    "data": null,
                    "className": "text-center",
                    "render": function(data, type, row, meta) {
                        return meta.row /*+ meta.settings._iDisplayStart*/ + 1;
                    }
                }, {
                    "data": "no_event",
                }, {
                    "data": "tgl_register",
                    "className": "text-center",
                    render: function(data, type, row, meta) {
                        return Convertyyyymmmddd(data);
                    }
                }, {
                    "data": "nm_peserta",
                }, {
                    "data": "jns_kel",
                    render: function(data, type, row, meta) {
                        return data == 1 ? "Laki-Laki" : "Perempuan";
                    }
                }, {
                    "data": "email",
                }, {
                    "data": null,
                    "orderable": false,
                    "className": "text-center",
                    render: function(data, type, row, meta) {
                        let html = "";
                        html += btnDataTable("Edit Register", "btn-outline-primary edit",
                            "fa fa-edit btn-outline-primary", true);
                        html += btnDataTable("Delete Register", "btn-outline-danger delete",
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
                        kd_peserta: data.kd_peserta
                    };
                    $("#FDelData p").html("Are you sure to delete data register <b>" + data.nm_peserta +
                        "</b> ?");
                    ShowModal("MDelData");
                });
            } else {
                table.ajax.reload();
            }
        }

        Add = function() {
            if (kd_event == "" || kd_event == null || kd_event == undefined) {
                MessageNotif("Silahkan Pilih Event Terlebih Dahulu!", "warning");
            } else {
                ShowData();
            }
        }

        ShowData = function(act = "Add", data = "") {
            let form_id = "#FAddEditData";
            $("h4[labelAddEdit]").text(act + " Data Register");
            processData = {
                action: act,
                kd_event: kd_event,
                no_event: (act == "Add" ? "" : data.no_event),
                tgl_register: (act == "Add" ? moment(new Date()).format("YYYY-MM-DD") : data.tgl_register),
                kd_peserta: (act == "Add" ? "" : data.kd_peserta),
            };
            $(form_id + " [name='no_event']").val(processData.no_event).change();
            $(form_id + " [name='tgl_register']").val(processData.tgl_register).change();
            $(form_id + " [name='kd_peserta']").removeAttr('disabled').find('option:not(:first)').remove().end();
            if (processData.action == "Add") {
                Loader("show");
                // Get Data Peserta Ready
                SendAjax({
                    url: $apiUrl + "Process/Register/PesertaReady",
                    param: {
                        kd_event: processData.kd_event
                    }
                }, function(result) {
                    let html = "";
                    $.each(result.data, function(index, value) {
                        html += '<option value="' + value.kd_peserta + '">' + value.nm_peserta +
                            '</option>';
                    });
                    if (html != "") {
                        $(html).insertAfter(form_id + " [name = 'kd_peserta'] option:first");
                        $(form_id + " .selectpicker").selectpicker('refresh');
                    }
                    $(form_id + " [name='kd_peserta']").val(processData.kd_peserta).change();
                    $(form_id).parsley().reset();
                    ShowModal("MAddEditData");
                }, function() {
                    Loader();
                });
            } else {
                let html = '<option value="' + data.kd_peserta + '">' + data.nm_peserta + '</option>';
                $(html).insertAfter(form_id + " [name = 'kd_peserta'] option:first");
                $(form_id + " .selectpicker").selectpicker('refresh');
                $(form_id + " [name='kd_peserta']").attr('disabled', true).val(processData.kd_peserta).change();
                $(form_id).parsley().reset();
                ShowModal("MAddEditData");
            }
        }

        Save = function() {
            let form_id = "#FAddEditData";
            if ($(form_id).parsley().validate()) {
                Loader("show");
                // Pass Data To Object
                processData.no_event = $(form_id + " [name='no_event']").val();
                processData.tgl_register = $(form_id + " [name='tgl_register']").val();
                processData.kd_peserta = $(form_id + " [name='kd_peserta']").val();
                //Setup Send Ajax
                let data = {
                    url: $apiUrl + "Process/Register/Save",
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
                url: $apiUrl + "Process/Register/Delete",
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
            DataEvent();
            Refresh();
            LoadPicker();
        });
    </script>
@endpush
