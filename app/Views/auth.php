<!DOCTYPE html>
<html lang="en" ng-app="auth">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login User</title>
    <link rel="stylesheet" href="<?=base_url('dist/css/auth.css')?>">
    <link rel="stylesheet" href="<?=base_url()?>/libs/sweetalert2/dist/sweetalert2.min.css">
</head>

<body ng-controller="userLogin">
    <div class="login-page">
        <div class="form">
            <form class="register-form" ng-submit="save()">
                <h2>Registered</h2>
                <input type="text" placeholder="Nama Pengguna" ng-model="model.fullname" required />
                <input type="text" placeholder="username" ng-model="model.username" required />
                <input type="password" placeholder="password" ng-model="model.password" required />
                <input type="text" placeholder="email address" ng-model="model.email" required />
                <button type="submit">create</button>
                <p class="message">Already registered? <a href="#">Sign In</a></p>
            </form>
            <form class="login-form" ng-submit="login()">
                <h2>Login User</h2>
                <div class="alert alert-danger" ng-show="error">Periksa Username dan Password Anda</div>
                <input type="text" placeholder="username" ng-model="model.username" required />
                <input type="password" placeholder="password" ng-model="model.password" required />
                <button type="submit">login</button>
                <a href="<?= $loginButton?>">Google</a>
                <p class="message">Not registered? <a href="#">Create an account</a></p>
            </form>
        </div>
    </div>
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="<?=base_url()?>/libs/angular/angular.min.js"></script>
    <script src="<?=base_url()?>/js/services/helper.services.js"></script>
    <script src="<?=base_url()?>/js/services/message.services.js"></script>
    <script src="<?=base_url()?>/libs/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="<?=base_url()?>/libs/swangular/swangular.js"></script>

    <script>
    angular.module('auth', ['helper.service', 'swangular',
            'message.service'
        ])
        .controller('userLogin', userLogin);

    function userLogin($scope, $http, helperServices,message) {
        $(".message a").click(function() {
            $("form").animate({
                height: "toggle",
                opacity: "toggle"
            }, "slow");
        });
        if('<?=session()->getFlashdata('pesan')?>'){
            message.info('<?=session()->getFlashdata('pesan')?>');
        }
        $scope.model = {};
        $scope.error = false
        $scope.model.username = 'Administrator';
        $scope.model.password = 'Admin@123';
        $scope.login = () => {
            $http({
                method: "post",
                url: "<?=base_url('auth/login')?>",
                data: $scope.model
            }).then(res => {
                if (res.data.role == 'Admin')
                    document.location.href = helperServices.url + 'admin/home';
                else
                    document.location.href = helperServices.url + 'home';
                    // document.location.href = helperServices.url;
            }, err => {
                $scope.error = true;
                message.error(err.data.messages.error, "Ok");
            })
        }
        $scope.save = () => {
            message.dialogmessage('Anda Yakin?', 'Ya', 'Tidak').then(x => {
                $http({
                    method: "post",
                    url: "<?=base_url('auth/register')?>",
                    data: $scope.model
                }).then(res => {
                    if (res.data){
                        message.info("Silahkan periksa email anda untuk confirmasi account", "Ok");
                    }
                        
                }, err => {
                    message.error(err.data.messages.message, "Ok");
                })
            })

        }
    }
    </script>
</body>

</html>