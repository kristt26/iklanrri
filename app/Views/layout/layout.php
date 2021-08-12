<!DOCTYPE html>
<html lang="en" ng-app="apps" ng-controller="indexController">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Advanced form elements</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="../../plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
    <link rel="stylesheet" href="../../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
    <link rel="stylesheet" href="../../plugins/bs-stepper/css/bs-stepper.min.css">
    <link rel="stylesheet" href="../../plugins/dropzone/min/dropzone.min.css">
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../dist/css/script.css">
    <!-- <link rel="stylesheet" href="../../libs/angular-datatables/dist/css/angular-datatables.css"> -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="SB-Mid-client-fs8aESL27KVF4nQ-"></script>
    <script src="../../plugins/jquery/jquery.min.js"></script>

    <style>
    .containerr {
        display: flex;
        height: 60vh;
        justify-content: center;
        align-items: center;
        direction: row;
    }

    @media screen {
        #print {
            /* font-family:verdana, arial, sans-serif; */
        }

        .screen {
            display: none;
        }

        .settt {
            display: block;
        }

        @page {
            size: landscape
        }
    }

    @media print {

        /* #print {font-family:georgia, times, serif;} */
        .screen {
            display: block;
        }

        .settt {
            display: none;
        }
    }
    </style>
</head>

<body class="hold-transition sidebar-mini navbar-fixed">
    <div class="wrapper">
        <?= $header?>
        <?= $sidebar?>
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1><?=$datamenu['menu']?></h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?=base_url('admin/home')?>">Home</a></li>
                                <li class="breadcrumb-item active"><?=$datamenu['menu']?></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container-fluid">
                    <?=$content?>
                </div>
            </section>
        </div>
        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 3.1.0
            </div>
            <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights
            reserved.
        </footer>
        <aside class="control-sidebar control-sidebar-dark">
        </aside>
    </div>

    <script src="../../libs/angular/angular.min.js"></script>
    <script src="../../js/apps.js"></script>
    <script src="../../js/services/helper.services.js"></script>
    <script src="../../js/services/auth.services.js"></script>
    <script src="../../js/services/admin.services.js"></script>
    <script src="../../js/services/message.services.js"></script>
    <script src="../../js/controllers/admin.controllers.js"></script>
    <script src="../../libs/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../../libs/swangular/swangular.js"></script>
    <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../libs/angular-datatables/dist/angular-datatables.min.js"></script>
    <script src="../../libs/angular-locale_id-id.js"></script>
    <script src="../../libs/input-mask/angular-input-masks-standalone.min.js"></script>
    <script src="../../libs/jquery.PrintArea.js"></script>
    <script src="../../libs/angular-base64-upload/dist/angular-base64-upload.min.js"></script>


    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../plugins/select2/js/select2.full.min.js"></script>
    <script src="../../plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
    <script src="../../plugins/moment/moment.min.js"></script>
    <script src="../../plugins/inputmask/jquery.inputmask.min.js"></script>
    <script src="../../plugins/daterangepicker/daterangepicker.js"></script>
    <script src="../../plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
    <script src="../../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="../../plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
    <script src="../../plugins/bs-stepper/js/bs-stepper.min.js"></script>
    <script src="../../plugins/dropzone/min/dropzone.min.js"></script>
    <script src="../../dist/js/adminlte.min.js"></script>
    <script src="../../dist/js/demo.js"></script>
    <script src="../../dist/js/script.js"></script>
    <script src="../../libs/loading/dist/loadingoverlay.min.js"></script>


</body>

</html>