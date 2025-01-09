<!DOCTYPE html>
<html lang="en">

<head>
    @include('layout.Head')
</head>

<body class="off-canvas-sidebar">
    <div class="wrapper wrapper-full-page">
        <div class="page-header login-page header-filter" filter-color="black"
            style="background-image: url('../../assets/img/login.jpg'); background-size: cover; background-position: top center;">
            <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
                        <form id="Flogin" data-parsley-errors-messages-disabled onsubmit="return false">
                            @csrf
                            <div class="card card-login card-hidden">
                                <div class="card-header card-header-rose text-center">
                                    <h4 class="card-title">Login</h4>
                                </div>
                                <div class="card-body">
                                    <span class="bmd-form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="material-icons">face</i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" name="username"
                                                placeholder="Username" required>
                                        </div>
                                    </span>
                                    <span class="bmd-form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="material-icons">lock_outline</i>
                                                </span>
                                            </div>
                                            <input type="password" class="form-control" name="password"
                                                placeholder="Password..." required>
                                        </div>
                                    </span>
                                </div>
                                <div class="card-footer justify-content-center">
                                    <button class="btn btn-rose btn-link btn-lg" onclick="Login()">Login</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('Layout.Footer')
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                // after 1000 ms we add the class animated to the login/register card
                $('.card').removeClass('card-hidden');
            }, 700);
        });
        Login = function() {
            let form_id = "#Flogin";
            if ($(form_id).parsley().validate()) {
                let data = {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "SignIn",
                    param: {
                        username: $(form_id + " [name='username']").val(),
                        password: $(form_id + " [name='password']").val()
                    }
                };
                SendAjax(data, function(result) {
                    MessageNotif(result.message, "success");
                    window.location.reload(true);
                }, function() {
                    Loader();
                });
            }
        }
    </script>
</body>

</html>
