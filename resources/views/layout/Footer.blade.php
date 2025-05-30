<script src="assets/js/core/jquery.min.js"></script>
<script src="assets/js/core/popper.min.js"></script>
<script src="assets/js/core/bootstrap-material-design.min.js"></script>
<script src="assets/js/plugins/perfect-scrollbar.min.js"></script>

<!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
<script src="assets/js/plugins/bootstrap-selectpicker.js"></script>
<!-- Plugin for the momentJs  -->
<script src="assets/js/plugins/moment.min.js"></script>
<!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
<script src="assets/js/plugins/bootstrap-datetimepicker.min.js"></script>

<script src="assets/js/plugins/jquery.dataTables.min.js"></script>
<script src="assets/plugins/parsleyjs/dist/parsley.js"></script>

<script src="assets/js/plugins/bootstrap-notify.js"></script>
<script src="assets/js/material-dashboard.js?v=2.2.2" type="text/javascript"></script>

<script>
    var $apiUrl = "api/";

    Loader = function(action = "hide") {
        if (action == "show") {
            $("#overlay").addClass("d-flex");
        } else {
            $("#overlay").removeClass("d-flex");
        }
    }

    MessageNotif = function(message = "", type = "notice", title = "Information") {
        // type = ['', 'info', 'danger', 'success', 'warning', 'rose', 'primary'];
        $.notify({
            title: title,
            message: message,
        }, {
            type: type,
            timer: 3000,
            placement: {
                from: "top",
                align: "right"
            }
        });
    }

    function PDataTables(dtu, columns = []) {
        let id = dtu.id ? dtu.id : "#datatables";
        let type = dtu.type ? dtu.type : "Ajax";
        let data = dtu.data ? dtu.data : {};
        if (type == "" || type == null) {
            return $(id).DataTable();
        } else {
            let config = dtu.config ? dtu.config : {};
            config.columns = columns;
            config.responsive = config.responsive ? config.responsive : {
                details: {
                    renderer: function(api, rowIdx, columns) {
                        let data = $.map(columns, function(col, i) {
                            return col.hidden ?
                                '<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' +
                                col.columnIndex + '">' +
                                '<td>' + col.title + ':' + '</td> ' +
                                '<td>' + col.data + '</td>' +
                                '</tr>' : ''
                        }).join('');
                        return data ? $('<table/>').append(data) : false;
                    }
                }
            };

            if (type == "Ajax") {
                let ajax = {
                    "url": data.url ? data.url : "",
                    "type": data.method ? data.method : "POST",
                    "contentType": "application/json",
                    // "headers": {
                    //     "Authorization": "Bearer " + Token
                    // },
                    "data": data.param ? data.param : function() {
                        var d = {};
                        return JSON.stringify(d);
                    },
                    "dataSrc": function(ext) {
                        if (ext.status == true) {
                            return ext.data;
                        } else {
                            alert("gagal"); // di ganti dengan custome pesan
                        }
                    },

                }
                config.processing = true;
                config.ajax = ajax;
            } else {
                config.aaData = data;
            }
            $(id).DataTable(config);
            return $(id).DataTable();
        }
    }
    btnDataTable = function(title, Class, icon, margin = false) {
        let btnClass = "btn btn-sm " + Class + (margin ? " mr-1" : "");
        var data = "<a role=\"button\" class=\"" + btnClass + "\" title=\"" + title + "\"><i class=\"" +
            icon + "\"> </i></a>";
        return data;
    }
    Dec0DataTable = $.fn.dataTable.render.number(',', '.', 0);
    Dec2DataTable = $.fn.dataTable.render.number(',', '.', 2);

    Convertyyyymmmddd = function(data) {
        if (data == "" || data == null || data == undefined) {
            return "";
        } else {
            var dd = data.substr(8, 2);
            var mm = data.substr(5, 2);
            var yyyy = data.substr(0, 4);
            return yyyy + "-" + mm + "-" + dd;
        }
    }

    LoadPicker = function() {
        $('.datepicker').datetimepicker({
            format: 'YYYY-MM-DD',
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-chevron-up",
                down: "fa fa-chevron-down",
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-screenshot',
                clear: 'fa fa-trash',
                close: 'fa fa-remove'
            }
        });
    }

    function ShowModal(id, option = "", scroll = false) {
        option = option ? option : {
            backdrop: 'static',
            keyboard: false
        };
        var md = $("#" + id);
        if (scroll) {
            md = md.on('hidden.bs.modal', function(event) {
                $('body').addClass('modal-open');
            }).modal(option);
        } else {
            md.modal(option);
        }
    }

    SendAjax = function(data, fc, fc1 = function() {}) {
        var url = data.url;
        var method = data.method ? data.method : "POST";
        var dataType = data.dataType ? data.dataType : "jsonp";
        var param = data.param ? data.param : {};
        var headers = data.headers ? data.headers : {
            // 'Authorization': 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhcHBWZXIiOiIwLjAuMCIsImV4cCI6NDcyNjM4OTEyMiwibG9jYWxlIjoiIiwibWFzdGVyVmVyIjoiIiwicGxhdGZvcm0iOiIiLCJwbGF0Zm9ybVZlciI6IiIsInVzZXJJZCI6IiJ9.QIZbmB5_9Xlap_gDhjETfMI6EAmR15yBtIQkWFWJkrg',
            // 'Content-Type': 'application/json'
        }

        $.ajax({
            type: method,
            // dataType: dataType,
            url: url,
            data: param,
            headers: headers,
            success: function(data, status, xhr) {
                let result = data;
                fc1();
                if (result.status) {
                    fc(result);
                } else {
                    MessageNotif(result.message, "warning");
                }
            },
            error: function(e) {
                fc1();
                // console.info(e);
                if (e.status == 500) {
                    MessageNotif(e.responseJSON.message, "danger");
                } else if (e.status == 400) {
                    MessageNotif(e.responseJSON.message, "warning");
                } else {
                    MessageNotif("Please, Check Your Connection!", "danger");
                }
            }
        });
    }

    Logout = function() {
        let data = {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "DestroySession",
        };
        SendAjax(data, function(result) {
            MessageNotif(result.message, "success");
            window.location.reload(true);
        }, function() {
            Loader();
        });
    }



    $(document).ready(function() {
        $sidebar = $('.sidebar');
        $sidebar_img_container = $sidebar.find('.sidebar-background');
        $full_page = $('.full-page');
    });
</script>

@stack('scripts')
