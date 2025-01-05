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
                                        <x-button type="button" class="btn-outline-info" icon="fa fa-refresh"
                                            label="Refresh" onclick="Refresh()" />
                                    </th>
                                </tr>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Event</th>
                                    <th class="text-center">Kriteria</th>
                                    <th class="text-center">Status</th>
                                    <th class="disabled-sorting text-center align-middle">Actions</th>
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
                <div class="material-datatables col-sm-12">
                    <table id="TblBobot" class="table table-striped table-no-bordered table-hover" cellspacing="0"
                        width="100%" style="width:100%">
                        <thead>
                            <tr>
                                <th colspan="6">
                                    <x-button type="button" class="btn-outline-success" icon="fa fa-plus" label="Add"
                                        onclick="AddDetail()" />
                                </th>
                            </tr>
                            <tr>
                                <th>No</th>
                                <th>Kriteria</th>
                                <th>Bobot</th>
                                <th class="disabled-sorting text-center">Actions</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-left">Total Bobot</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <x-button type="button" class="btn-outline-secondary mr-1" label="Close" data-dismiss="modal" />
            <x-button type="submit" class="btn-outline-primary" onclick="Save()">Save</x-button>
        </div>
    </x-modal-form>
    {{-- Add Edit Detail Modal --}}
    <x-modal-form id="AddEditDataDetail" title="labelAddEdit">
        <div class="modal-body">
            <div class="row">
                <x-form-group type="select" class="col-sm-12 col-md-12" label="Kriteria" name="kd_kriteria" required>
                    <option value="" disabled>--Choose Kriteria--</option>
                </x-form-group>
                <x-form-group class="col-sm-12 col-md-12" label="Bobot" name="bobot" required />
            </div>
        </div>
        <div class="modal-footer">
            <x-button type="button" class="btn-outline-secondary mr-1" label="Close" data-dismiss="modal" />
            <x-button type="submit" class="btn-outline-primary" onclick="SaveDetail()">Save</x-button>
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
        let table, table1, id_tbl = "#datatables";
        let processData = {};
        let dtKecuali = [];
        let nm_event = "";
        let dtDetail = {};

        Refresh = function() {
            if (!$.fn.DataTable.isDataTable(id_tbl)) {
                let dtu = {
                    id: id_tbl,
                    data: {
                        url: $apiUrl + "MasterData/Bobot/List"
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
                    "data": "ckriteria",
                    "className": "text-right",
                    render: Dec0DataTable
                }, {
                    "data": "setup",
                    render: function(data, type, row, meta) {
                        var html = (data == 1) ? "Done" : "Not Set";
                        return html
                    }
                }, {
                    "data": null,
                    "orderable": false,
                    "className": "text-center",
                    render: function(data, type, row, meta) {
                        let html = "";
                        html += btnDataTable("Edit Bobot", "btn-outline-primary edit",
                            "fa fa-edit btn-outline-primary", true);
                        html += btnDataTable("Delete Bobot", "btn-outline-danger delete",
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
                        kd_event: data.kd_event
                    };
                    $("#FDelData p").html("Are you sure to delete data setup Bobot <b>" + data.nm_event +
                        "</b> ?");
                    ShowModal("MDelData");
                });
            } else {
                table.ajax.reload();
            }
        }

        ShowData = function(act = "Add", data = "") {
            let form_id = "#FAddEditData";
            nm_event = data.nm_event;
            $("h4[labelAddEdit]").text("Setup Data Bobot");
            dtKecuali = [];
            // Get Data Bobot
            SendAjax({
                url: $apiUrl + "MasterData/Bobot/DataBobot",
                param: {
                    kd_event: data.kd_event
                }
            }, function(result) {
                processData = {
                    kd_event: data.kd_event,
                    dtbobot: result.data
                };
                $.each(processData.dtbobot, function(index, value) {
                    dtKecuali.push(value.kd_kriteria);
                });
                LoadBobot(processData.dtbobot);
                $(form_id).parsley().reset();
                ShowModal("MAddEditData");
            }, function() {
                Loader();
            });
        }

        LoadBobot = function(data) {
            if (!$.fn.DataTable.isDataTable("#TblBobot")) {
                let dtu = {
                    id: "#TblBobot",
                    type: "manual",
                    data: data,
                    config: {
                        footerCallback: function(row, data, start, end, display) {
                            let api = this.api();
                            // Remove the formatting to get integer data for summation
                            let intVal = function(i) {
                                return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 :
                                    typeof i === 'number' ? i : 0;
                            };

                            // Total over all pages
                            TotPerse = api.column(2).data().reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                            // Update footer
                            $(api.column(2).footer()).html(Dec2DataTable.display(TotPerse));
                        },
                        bFilter: false,
                        bPaginate: false,
                        bLengthChange: false,
                        bInfo: false,
                    }
                };
                table1 = PDataTables(dtu, [{
                    "data": null,
                    "className": "text-center",
                    "render": function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }, {
                    "data": "nm_kriteria",
                }, {
                    "data": "bobot",
                    "className": "text-center",
                    render: Dec2DataTable
                }, {
                    "data": null,
                    "orderable": false,
                    "className": "text-center",
                    render: function(data, type, row, meta) {
                        let html = "";
                        html += btnDataTable("Setup Bobot", "btn-outline-primary edit",
                            "fa fa-edit btn-outline-primary", true);
                        html += btnDataTable("Remove Bobot", "btn-outline-danger delete",
                            "fa fa-trash btn-outline-danger");
                        return html;
                    }
                }]);
                table1.on('click', '.edit', function() {
                    $tr = $(this).closest('tr');
                    tridx = table1.row($tr).index();
                    var data = table1.row($tr).data();
                    ShowDataDetail("Edit", data);
                });
                table1.on('click', '.delete', function() {
                    $tr = $(this).closest('tr');
                    tridx = table1.row($tr).index();
                    var data = table1.row($tr).data();
                    processData.dtbobot.splice(tridx, 1);
                    dtKecuali.splice(dtKecuali.indexOf(data.kd_kriteria), 1);
                    LoadBobot(processData.dtbobot);
                });
            } else {
                $('#TblBobot').DataTable().clear();
                $('#TblBobot').DataTable().rows.add(data);
                $('#TblBobot').DataTable().draw();
            }
        }

        AddDetail = function() {
            ShowDataDetail();
        }
        ShowDataDetail = function(act = "Add", data = "") {
            let form_id = "#FAddEditDataDetail";
            let lbldetail = act + " Data Kriteria (" + nm_event + ")";
            $("#MAddEditDataDetail h4[labelAddEdit]").text(lbldetail);

            dtDetail = {
                action: act,
                kd_kriteria: (act == "Add" ? "" : data.kd_kriteria),
                nm_kriteria: (act == "Add" ? "" : data.nm_kriteria),
                bobot: (act == "Add" ? "" : data.bobot),
            };
            $(form_id + " [name='kd_kriteria']").removeAttr('disabled').find('option:not(:first)').remove().end();
            if (act == "Add") {

                SendAjax({
                    url: $apiUrl + "MasterData/Bobot/KriteriaReady",
                    param: {
                        kd_event: processData.kd_event,
                        dtbobot: dtKecuali
                    }
                }, function(result) {
                    let html = "";
                    $.each(result.data, function(index, value) {
                        html += '<option value="' + value.kd_kriteria + '">' + value.nm_kriteria +
                            '</option>';
                    });
                    if (html != "") {
                        $(html).insertAfter(form_id + " [name = 'kd_kriteria'] option:first");
                        $(form_id + " .selectpicker").selectpicker('refresh');
                    }
                    $(form_id + " [name='kd_kriteria']").val(dtDetail.kd_kriteria).change();
                    $(form_id + " [name='bobot']").val(dtDetail.bobot).change();
                    $(form_id).parsley().reset();
                    ShowModal("MAddEditDataDetail", undefined, true);
                }, function() {
                    Loader();
                });

            } else {
                let html = '<option value="' + dtDetail.kd_kriteria + '">' + dtDetail.nm_kriteria + '</option>';
                $(html).insertAfter(form_id + " [name = 'kd_kriteria'] option:first");
                $(form_id + " .selectpicker").selectpicker('refresh');
                $(form_id + " [name='kd_kriteria']").attr('disabled', true).val(dtDetail.kd_kriteria).change();
                $(form_id + " [name='bobot']").val(dtDetail.bobot).change();
                $(form_id).parsley().reset();
                ShowModal("MAddEditDataDetail", undefined, true);
            }
        }
        SaveDetail = function() {
            let form_id = "#FAddEditDataDetail";
            if ($(form_id).parsley().validate()) {
                if (dtDetail.action == 'Add') {
                    processData.dtbobot.push({
                        kd_kriteria: $(form_id + " [name='kd_kriteria']").val(),
                        nm_kriteria: $(form_id + " [name='kd_kriteria'] [value='" + $(form_id +
                            " [name='kd_kriteria']").val() + "']").text(),
                        bobot: $(form_id + " [name='bobot']").val(),
                    });
                    dtKecuali.push($(form_id + " [name='kd_kriteria']").val());
                    LoadBobot(processData.dtbobot);
                    ShowModal("MAddEditDataDetail", "hide");
                } else {
                    processData.dtbobot[tridx].kd_kriteria = $(form_id + " [name='kd_kriteria']").val();
                    processData.dtbobot[tridx].nm_kriteria = $(form_id + " [name='kd_kriteria'] [value='" +
                        $(form_id + " [name='kd_kriteria']").val() + "']").text();
                    processData.dtbobot[tridx].bobot = $(form_id + " [name='bobot']").val();
                    LoadBobot(processData.dtbobot);
                    ShowModal("MAddEditDataDetail", "hide");
                }
            }
        }

        Save = function() {
            let form_id = "#FAddEditData";
            if (TotPerse == 100) {
                Loader("show");
                let data = {
                    url: $apiUrl + "MasterData/Bobot/Save",
                    param: processData
                };
                SendAjax(data, function(result) {
                    MessageNotif(result.message, "success");
                    Refresh();
                    ShowModal("MAddEditData", "hide");
                }, function() {
                    Loader();
                });
            } else {
                MessageNotif("Total Persen Harus 100%!", "warning");
            }
        }
        Delete = function() {
            Loader("show");
            let data = {
                url: $apiUrl + "MasterData/Bobot/Delete",
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
