<script src="assets/js/core/jquery.min.js"></script>
<script src="assets/js/core/popper.min.js"></script>
<script src="assets/js/core/bootstrap-material-design.min.js"></script>
<script src="assets/js/plugins/perfect-scrollbar.min.js"></script>

<!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
<script src="assets/js/plugins/bootstrap-selectpicker.js"></script>

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

    function ShowModal(id, option = "") {
        option = option ? option : {
            backdrop: 'static',
            keyboard: false
        };
        var md = $("#" + id);
        $("#" + id).modal(option);
    }

    SendAjax = function(data, fc, fc1 = function() {}) {
        var url = data.url;
        var method = data.method ? data.method : "POST";
        var dataType = data.dataType ? data.dataType : "jsonp";
        var param = data.param ? data.param : {};

        $.ajax({
            type: method,
            // dataType: dataType,
            url: url,
            data: param,
            // headers: {
            //     'Authorization': 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhcHBWZXIiOiIwLjAuMCIsImV4cCI6NDcyNjM4OTEyMiwibG9jYWxlIjoiIiwibWFzdGVyVmVyIjoiIiwicGxhdGZvcm0iOiIiLCJwbGF0Zm9ybVZlciI6IiIsInVzZXJJZCI6IiJ9.QIZbmB5_9Xlap_gDhjETfMI6EAmR15yBtIQkWFWJkrg',
            //     'Content-Type': 'application/json'
            // },
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
                console.info(e);
                MessageNotif("Please, Check Your Connection!", "danger");
            }
        });
    }



    $(document).ready(function() {
        $sidebar = $('.sidebar');
        $sidebar_img_container = $sidebar.find('.sidebar-background');
        $full_page = $('.full-page');
    });
</script>

@stack('scripts')
