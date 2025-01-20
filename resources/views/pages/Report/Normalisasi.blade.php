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
                                        <th class="text-center">No Event</th>
                                        <th class="text-center">Nama</th>
                                        <th class="text-center">Score</th>
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
                <div class="material-datatables col-sm-12">
                    <table id="tableMaple" class="table table-striped table-no-bordered table-hover" cellspacing="0"
                        width="100%" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kriteria</th>
                                <th>Tipe</th>
                                <th>Bobot</th>
                                <th>Min/Max</th>
                                <th>Nilai</th>
                                <th>Matrix</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-left">Nilai Akhir</th>
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

@push('scripts')
    <script type="text/javascript">
        let table, id_tbl = "#datatables";
        let processData = {};
        let kd_event = "";
        let kd_peserta = "";
        let nm_peserta = "";

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
                        url: $apiUrl + "Report/Normalisasi/Keputusan",
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
                    "data": "nm_peserta",
                }, {
                    "data": "nilai",
                    "className": "text-right",
                    render: Dec2DataTable
                }, {
                    "data": "setup",
                    "className": "text-right",
                    render: function(data, type, row, meta) {
                        let html = "";
                        if (data = 1) {
                            html = "Juara " + (meta.row + 1);
                        } else {
                            html = "Nilai Belum Masuk";
                        }
                        return html;
                    }
                }, {
                    "data": null,
                    "orderable": false,
                    "className": "text-center",
                    render: function(data, type, row, meta) {
                        let html = "";
                        html += btnDataTable("View Detail Nilai", "btn-outline-primary view",
                            "fa fa-eye btn-outline-primary", true);
                        return html;
                    }
                }]);
                table.on('click', '.view', function() {
                    $tr = $(this).closest('tr');
                    var data = table.row($tr).data();
                    ShowData("Edit", data);
                });
            } else {
                table.ajax.reload();
            }
        }

        ShowData = function(act = "Add", data = "") {
            let form_id = "#FAddEditData";
            nm_peserta = data.nm_peserta;
            kd_peserta = data.kd_peserta;
            $("#MAddEditData h4[labelAddEdit]").text("Nilai Peserta " + nm_peserta);
            LoadNilai();
            ShowModal("MAddEditData");
        }

        LoadNilai = function() {
            if (!$.fn.DataTable.isDataTable("#tableMaple")) {
                let dtu = {
                    id: "#tableMaple",
                    data: {
                        url: $apiUrl + "Report/Normalisasi/NilaiEvent",
                        param: function() {
                            var d = {};
                            d.kd_event = kd_event;
                            d.kd_peserta = kd_peserta;
                            return JSON.stringify(d);
                        }
                    },
                    config: {
                        footerCallback: function(row, data, start, end, display) {
                            let api = this.api();
                            // Remove the formatting to get integer data for summation
                            let intVal = function(i) {
                                return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 :
                                    typeof i === 'number' ? i : 0;
                            };

                            let NilaiMatrix = data.reduce(function(a, b) {
                                    return intVal(a) + (intVal(b.bobot) *
                                        (intVal(b.nilai) / intVal(b.maxnilai)));
                                },
                                0);

                            // Update footer
                            $(api.column(6).footer()).html(Dec2DataTable.display(NilaiMatrix));
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
                        return meta.row /*+ meta.settings._iDisplayStart*/ + 1;
                    }
                }, {
                    "data": "nm_kriteria",
                }, {
                    "data": "tipe",
                    "className": "text-right",
                    render: function(data, type, row, meta) {
                        let html = (data == 1 ? "Benefit" : "Cost");
                        return html;
                    }
                }, {
                    "data": "bobot",
                    "className": "text-right",
                    render: Dec2DataTable
                }, {
                    "data": null,
                    "className": "text-right",
                    render: function(data, type, row, meta) {
                        let html = (data.type == 1 ? data.maxnilai : data.minnilai);
                        return Dec2DataTable.display(html);
                    }
                }, {
                    "data": "nilai",
                    "className": "text-right",
                    render: Dec2DataTable
                }, {
                    "data": null,
                    "className": "text-right",
                    render: function(data, type, row, meta) {
                        let nilai = 0;
                        if (data.tipe == 1) {
                            nilai = Dec2DataTable.display(data.nilai / data.maxnilai);
                        } else {
                            nilai = Dec2DataTable.display(data.minnilai / data.nilai);
                        }
                        return nilai;
                    }
                }]);
            } else {
                table1.ajax.reload();
            }
        }

        DownloadReport = function() {

        }

        $(document).ready(function() {
            Refresh();
            DataEvent();
        });
    </script>
@endpush
