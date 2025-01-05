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
                                    <th class="text-center align-middle" colspan="3">Event</th>
                                    <th class="text-center align-middle" rowspan="2">Status</th>
                                    <th class="disabled-sorting text-center align-middle" rowspan="2">Actions</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">Kouta</th>
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
                <x-form-group class="col-sm-12 col-md-12" label="Event" name="nm_event" required />
                <x-form-group type="date" class="col-sm-12 col-md-12" label="Tanggal" name="tgl_event" required />
                <x-form-group class="col-sm-12 col-md-12" label="Kouta" name="kuota" required />
                <x-form-group type="select" class="col-sm-12 col-md-12" label="Status" name="flag_active" required>
                    <option value="" disabled>--Choose Status--</option>
                    <option value="1">Aktif</option>
                    <option value="0">Non Aktif</option>
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
                        url: $apiUrl + "MasterData/Event/List"
                    }
                };
                table = PDataTables(dtu, [{
                    "data": null,
                    "className": "text-center",
                    "render": function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }, {
                    "data": "nm_event",
                }, {
                    "data": "tgl_event",
                    "className": "text-center",
                    render: function(data, type, row, meta) {
                        return Convertyyyymmmddd(data);
                    }
                }, {
                    "data": "kuota",
                    "className": "text-right",
                    render: Dec0DataTable
                }, {
                    "data": "flag_active",
                    render: function(data, type, row, meta) {
                        return data == 1 ? "Aktif" : "Non Aktif";
                    }
                }, {
                    "data": null,
                    "orderable": false,
                    "className": "text-center",
                    render: function(data, type, row, meta) {
                        let html = "";
                        html += btnDataTable("Edit Event", "btn-outline-primary edit",
                            "fa fa-edit btn-outline-primary", true);
                        html += btnDataTable("Delete Event", "btn-outline-danger delete",
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
                    $("#FDelData p").html("Are you sure to delete data Event <b>" + data.nm_event + "</b> ?");
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
            $("h4[labelAddEdit]").text(act + " Data Event");
            processData = {
                action: act,
                kd_event: (act == "Add" ? 0 : data.kd_event),
                nm_event: (act == "Add" ? "" : data.nm_event),
                tgl_event: (act == "Add" ? moment(new Date()).format("YYYY-MM-DD") : data.tgl_event),
                kuota: (act == "Add" ? 0 : data.kuota),
                flag_active: (act == "Add" ? "" : data.flag_active),
            };
            $(form_id + " [name='nm_event']").val(processData.nm_event).change();
            $(form_id + " [name='tgl_event']").val(processData.tgl_event).change();
            $(form_id + " [name='kuota']").val(processData.kuota).change();
            $(form_id + " [name='flag_active']").val(processData.flag_active).change();
            $(form_id).parsley().reset();
            ShowModal("MAddEditData");
        }

        Save = function() {
            let form_id = "#FAddEditData";
            if ($(form_id).parsley().validate()) {
                Loader("show");
                // Pass Data To Object
                processData.nm_event = $(form_id + " [name='nm_event']").val();
                processData.tgl_event = $(form_id + " [name='tgl_event']").val();
                processData.kuota = $(form_id + " [name='kuota']").val();
                processData.flag_active = $(form_id + " [name='flag_active']").val();
                //Setup Send Ajax
                let data = {
                    url: $apiUrl + "MasterData/Event/Save",
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
                url: $apiUrl + "MasterData/Event/Delete",
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
