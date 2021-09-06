<!DOCTYPE html>
<html lang="en" ng-app="auth">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login User</title>
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />
    <link rel="stylesheet" href="<?=base_url('dist/css/auth.css')?>">
    <link rel="stylesheet" href="<?=base_url('dist/css/google.css')?>">
    <link rel="stylesheet" href="<?=base_url()?>/libs/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
</head>

<body ng-controller="userLogin">
    <div class="login-page">
        <div class="form">
            <form class="register-form" ng-submit="save()">
                <div ng-if="setForm=='create'">
                    <h2>Registered</h2>
                    <input type="text" placeholder="Nama Pengguna" ng-model="model.fullname" required />
                    <input type="text" placeholder="username" ng-model="model.username" required />
                    <input type="password" placeholder="password" ng-model="model.password" required />
                    <input type="text" placeholder="email address" ng-model="model.email" required />
                    <button type="submit">create</button>
                </div>
                <div ng-if="setForm=='reset'">
                    <h2>Reset</h2>
                    <input type="text" placeholder="email address" ng-model="model.email" required />
                    <button type="submit">reset</button>
                </div>
                <p class="message">Already registered? <a href="#">Sign In</a></p>
            </form>
            <form class="login-form" ng-submit="login()">
                <h2>Login User</h2>
                <div class="alert alert-danger" ng-if="error">Periksa Username dan Password Anda</div>
                <input type="text" placeholder="username" ng-model="model.username" required />
                <input type="password" placeholder="password" ng-model="model.password" required />
                <button type="submit">login</button><br><br>
                <a href="<?=$loginButton?>" class="google">Sign in with google</a>
                <p class="message">Not registered? <a href="#" ng-click="Form('create')">Create an account</a></p>
                <p class="message">forgot password? <a href="#" ng-click="Form('reset')">Reset Password</a></p>
            </form>
        </div>
    </div>
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="<?=base_url()?>/libs/angular/angular.min.js"></script>
    <script src="<?=base_url()?>/js/services/helper.services.js"></script>
    <script src="<?=base_url()?>/js/services/message.services.js"></script>
    <script src="<?=base_url()?>/libs/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="<?=base_url()?>/libs/swangular/swangular.js"></script>
    <script src="../../libs/loading/dist/loadingoverlay.min.js"></script>

    <script>
    angular.module('auth', ['helper.service', 'swangular', 'message.service'])
        .controller('userLogin', userLogin);

    function userLogin($scope, $http, helperServices, message) {
        $(".message a").click(function() {
            $("form").animate({
                height: "toggle",
                opacity: "toggle"
            }, "slow");
        });
        if ('<?=session()->getFlashdata('pesan')?>') {
            message.info('<?=session()->getFlashdata('pesan')?>');
        }
        $scope.model = {};
        $scope.setForm = "";
        $scope.error = false
        $scope.model.username = 'Administrator';
        $scope.model.password = 'Admin@123';
        $scope.Form = (set) => {
            $scope.setForm = set;
        }
        $scope.login = () => {
            $http({
                method: "post",
                url: "<?=base_url('auth/login')?>",
                data: $scope.model
            }).then(res => {
                if (res.data.role == 'Admin')
                    document.location.href = helperServices.url + 'admin/home';
                else if (res.data.role == 'Siaran')
                    document.location.href = helperServices.url + 'siaran/home';
                else if (res.data.role == 'Pimpinan')
                    document.location.href = helperServices.url + 'pimpinan/home';
                else
                    document.location.href = helperServices.url + 'home';
                // document.location.href = helperServices.url;
            }, err => {
                $scope.error = true;
                message.error(err.data.messages.error, "Ok");
            })
        }
        $scope.save = () => {
            if ($scope.setForm == 'create') {
                message.dialogmessage('Anda Yakin?', 'Ya', 'Tidak').then(x => {
                    $.LoadingOverlay("show");
                    $http({
                        method: "post",
                        url: "<?=base_url('auth/register')?>",
                        data: $scope.model
                    }).then(res => {
                        $.LoadingOverlay("hide");
                        message.info("Silahkan periksa email anda untuk confirmasi account", "Ok");
                    }, err => {
                        $.LoadingOverlay("hide");
                        message.error(err.data.messages.message, "Ok");
                        console.log(err.data.messages);
                    })
                })
            } else {
                message.dialogmessage('Anda Yakin ingin mereset password?', 'Ya', 'Tidak').then(x => {
                    $.LoadingOverlay("show");
                    $http({
                        method: "get",
                        url: "<?=base_url('auth/reset/')?>/" + $scope.model.email
                    }).then(res => {
                        $.LoadingOverlay("hide");
                        message.info("Silahkan periksa email anda", "Ok");
                    }, err => {
                        $.LoadingOverlay("hide");
                        message.error(err.data.messages.message, "Ok");
                        console.log(err.data.messages);
                    })
                })
            }
        }
    }
    </script>
</body>

</html>