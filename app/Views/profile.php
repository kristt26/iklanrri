            <!-- Breadcrumb -->
            <div ng-controller="profileController">
                <nav aria-label="breadcrumb" class="main-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
                    </ol>
                </nav>
                <!-- /Breadcrumb -->

                <div class="row gutters-sm">
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center text-center">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin"
                                        class="rounded-circle" width="150">
                                    <div class="mt-3">
                                        <h4>{{datas.fullname}}</h4>
                                        <a ng-if="!datas.login_oauth_uid" href="<?= $loginButton?>"
                                            class="btn btn-secondary"><svg enable-background="new 0 0 512 512"
                                                width="24" height="24" id="Layer_1" version="1.1" viewBox="0 0 512 512"
                                                xml:space="preserve" xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink">
                                                <g>
                                                    <path
                                                        d="M42.4,145.9c15.5-32.3,37.4-59.6,65-82.3c37.4-30.9,80.3-49.5,128.4-55.2c56.5-6.7,109.6,4,158.7,33.4   c12.2,7.3,23.6,15.6,34.5,24.6c2.7,2.2,2.4,3.5,0.1,5.7c-22.3,22.2-44.6,44.4-66.7,66.8c-2.6,2.6-4,2.4-6.8,0.3   c-64.8-49.9-159.3-36.4-207.6,29.6c-8.5,11.6-15.4,24.1-20.2,37.7c-0.4,1.2-1.2,2.3-1.8,3.5c-12.9-9.8-25.9-19.6-38.7-29.5   C72.3,169,57.3,157.5,42.4,145.9z"
                                                        fill="#E94335" />
                                                    <path
                                                        d="M126,303.8c4.3,9.5,7.9,19.4,13.3,28.3c22.7,37.2,55.1,61.1,97.8,69.6c38.5,7.7,75.5,2.5,110-16.8   c1.2-0.6,2.4-1.2,3.5-1.8c0.6,0.6,1.1,1.3,1.7,1.8c25.8,20,51.7,40,77.5,60c-12.4,12.3-26.5,22.2-41.5,30.8   c-43.5,24.8-90.6,34.8-140.2,31C186.3,501.9,133,477.5,89,433.5c-19.3-19.3-35.2-41.1-46.7-66c10.7-8.2,21.4-16.3,32.1-24.5   C91.6,329.9,108.8,316.9,126,303.8z"
                                                        fill="#34A853" />
                                                    <path
                                                        d="M429.9,444.9c-25.8-20-51.7-40-77.5-60c-0.6-0.5-1.2-1.2-1.7-1.8c8.9-6.9,18-13.6,25.3-22.4   c12.2-14.6,20.3-31.1,24.5-49.6c0.5-2.3,0.1-3.1-2.2-3c-1.2,0.1-2.3,0-3.5,0c-40.8,0-81.7-0.1-122.5,0.1c-4.5,0-5.5-1.2-5.4-5.5   c0.2-29,0.2-58,0-87c0-3.7,1-4.7,4.7-4.7c74.8,0.1,149.6,0.1,224.5,0c3.2,0,4.5,0.8,5.3,4.2c6.1,27.5,5.7,55.1,2,82.9   c-3,22.2-8.4,43.7-16.7,64.5c-12.3,30.7-30.4,57.5-54.2,80.5C431.6,443.8,430.7,444.3,429.9,444.9z"
                                                        fill="#4285F3" />
                                                    <path
                                                        d="M126,303.8c-17.2,13.1-34.4,26.1-51.6,39.2c-10.7,8.1-21.4,16.3-32.1,24.5C34,352.1,28.6,335.8,24.2,319   c-8.4-32.5-9.7-65.5-5.1-98.6c3.6-26,11.1-51,23.2-74.4c15,11.5,29.9,23.1,44.9,34.6c12.9,9.9,25.8,19.7,38.7,29.5   c-2.2,10.7-5.3,21.2-6.3,32.2c-1.8,20,0.1,39.5,5.8,58.7C125.8,301.8,125.9,302.8,126,303.8z"
                                                        fill="#FABB06" />
                                                </g>
                                            </svg></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mt-3">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                    <h6 class="mb-0">
                                        <svg enable-background="new 0 0 512 512" width="24" height="24" id="Layer_1"
                                            version="1.1" viewBox="0 0 512 512" xml:space="preserve"
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <g>
                                                <path
                                                    d="M42.4,145.9c15.5-32.3,37.4-59.6,65-82.3c37.4-30.9,80.3-49.5,128.4-55.2c56.5-6.7,109.6,4,158.7,33.4   c12.2,7.3,23.6,15.6,34.5,24.6c2.7,2.2,2.4,3.5,0.1,5.7c-22.3,22.2-44.6,44.4-66.7,66.8c-2.6,2.6-4,2.4-6.8,0.3   c-64.8-49.9-159.3-36.4-207.6,29.6c-8.5,11.6-15.4,24.1-20.2,37.7c-0.4,1.2-1.2,2.3-1.8,3.5c-12.9-9.8-25.9-19.6-38.7-29.5   C72.3,169,57.3,157.5,42.4,145.9z"
                                                    fill="#E94335" />
                                                <path
                                                    d="M126,303.8c4.3,9.5,7.9,19.4,13.3,28.3c22.7,37.2,55.1,61.1,97.8,69.6c38.5,7.7,75.5,2.5,110-16.8   c1.2-0.6,2.4-1.2,3.5-1.8c0.6,0.6,1.1,1.3,1.7,1.8c25.8,20,51.7,40,77.5,60c-12.4,12.3-26.5,22.2-41.5,30.8   c-43.5,24.8-90.6,34.8-140.2,31C186.3,501.9,133,477.5,89,433.5c-19.3-19.3-35.2-41.1-46.7-66c10.7-8.2,21.4-16.3,32.1-24.5   C91.6,329.9,108.8,316.9,126,303.8z"
                                                    fill="#34A853" />
                                                <path
                                                    d="M429.9,444.9c-25.8-20-51.7-40-77.5-60c-0.6-0.5-1.2-1.2-1.7-1.8c8.9-6.9,18-13.6,25.3-22.4   c12.2-14.6,20.3-31.1,24.5-49.6c0.5-2.3,0.1-3.1-2.2-3c-1.2,0.1-2.3,0-3.5,0c-40.8,0-81.7-0.1-122.5,0.1c-4.5,0-5.5-1.2-5.4-5.5   c0.2-29,0.2-58,0-87c0-3.7,1-4.7,4.7-4.7c74.8,0.1,149.6,0.1,224.5,0c3.2,0,4.5,0.8,5.3,4.2c6.1,27.5,5.7,55.1,2,82.9   c-3,22.2-8.4,43.7-16.7,64.5c-12.3,30.7-30.4,57.5-54.2,80.5C431.6,443.8,430.7,444.3,429.9,444.9z"
                                                    fill="#4285F3" />
                                                <path
                                                    d="M126,303.8c-17.2,13.1-34.4,26.1-51.6,39.2c-10.7,8.1-21.4,16.3-32.1,24.5C34,352.1,28.6,335.8,24.2,319   c-8.4-32.5-9.7-65.5-5.1-98.6c3.6-26,11.1-51,23.2-74.4c15,11.5,29.9,23.1,44.9,34.6c12.9,9.9,25.8,19.7,38.7,29.5   c-2.2,10.7-5.3,21.2-6.3,32.2c-1.8,20,0.1,39.5,5.8,58.7C125.8,301.8,125.9,302.8,126,303.8z"
                                                    fill="#FABB06" />
                                            </g>
                                        </svg> Google
                                    </h6>
                                    <span class="text-secondary">{{datas.login_oauth_uid}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Full Name</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{datas.fullname}}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Email</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{datas.email}}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Phone</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{datas.kontak}}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Address</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{datas.alamat}}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <a class="btn btn-info " ng-click="edit()">Edit</a>
                                        <a class="btn btn-warning " ng-click="reset()">Reset Password</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="editProfile" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form role="form" ng-submit="save(model)">
                                <div class="modal-header bg-rri">
                                    <h5 class="modal-title">Tambah Data</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="nama" class="col-form-label col-form-label-sm">Nama</label>
                                        <input type="text" class="form-control  form-control-sm" id="nama"
                                            ng-model="model.fullname" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="kontak" class="col-form-label col-form-label-sm">Phone</label>
                                        <input type="text" class="form-control  form-control-sm" id="kontak"
                                            ng-model="model.kontak" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat" class="col-form-label col-form-label-sm">Alamat</label>
                                        <textarea class="form-control  form-control-sm" id="alamat" cols="4"
                                            ng-model="model.alamat"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary btn-sm pull-right">Ubah</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="showReset" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form role="form" ng-submit="resetPassword(model)">
                                <div class="modal-header bg-rri">
                                    <h5 class="modal-title">Reset Password</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="oldPass" class="col-form-label col-form-label-sm">Old Password</label>
                                        <input type="password" class="form-control  form-control-sm" id="oldPass"
                                            ng-model="model.password" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="newpass" class="col-form-label col-form-label-sm">New Password</label>
                                        <input type="password" class="form-control  form-control-sm" id="newpass"
                                            ng-model="model.newpass" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary btn-sm pull-right">Ubah</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <style type="text/css">
.card {
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1), 0 1px 2px 0 rgba(0, 0, 0, .06);
}

.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 0 solid rgba(0, 0, 0, .125);
    border-radius: .25rem;
}

.card-body {
    flex: 1 1 auto;
    min-height: 1px;
    padding: 1rem;
}

.gutters-sm {
    margin-right: -8px;
    margin-left: -8px;
}

.gutters-sm>.col,
.gutters-sm>[class*=col-] {
    padding-right: 8px;
    padding-left: 8px;
}

.mb-3,
.my-3 {
    margin-bottom: 1rem !important;
}

.bg-gray-300 {
    background-color: #e2e8f0;
}

.h-100 {
    height: 100% !important;
}

.shadow-none {
    box-shadow: none !important;
}

.bg-rri {
    background-color: #00a8e6 !important;
}

.bg-rri>.card-header .btn-tool,
.bg-gradient-danger>.card-header .btn-tool,
.card-danger:not(.card-outline)>.card-header .btn-tool {
    color: rgba(255, 255, 255, 0.8);
}

.bg-rri>.card-header .btn-tool:hover,
.bg-gradient-danger>.card-header .btn-tool:hover,
.card-danger:not(.card-outline)>.card-header .btn-tool:hover {
    color: #fff;
}

.card.bg-rri .bootstrap-datetimepicker-widget .table td,
.card.bg-rri .bootstrap-datetimepicker-widget .table th,
.card.bg-gradient-danger .bootstrap-datetimepicker-widget .table td,
.card.bg-gradient-danger .bootstrap-datetimepicker-widget .table th {
    border: none;
}

.card.bg-rri .bootstrap-datetimepicker-widget table thead tr:first-child th:hover,
.card.bg-rri .bootstrap-datetimepicker-widget table td.day:hover,
.card.bg-rri .bootstrap-datetimepicker-widget table td.hour:hover,
.card.bg-rri .bootstrap-datetimepicker-widget table td.minute:hover,
.card.bg-rri .bootstrap-datetimepicker-widget table td.second:hover,
.card.bg-gradient-danger .bootstrap-datetimepicker-widget table thead tr:first-child th:hover,
.card.bg-gradient-danger .bootstrap-datetimepicker-widget table td.day:hover,
.card.bg-gradient-danger .bootstrap-datetimepicker-widget table td.hour:hover,
.card.bg-gradient-danger .bootstrap-datetimepicker-widget table td.minute:hover,
.card.bg-gradient-danger .bootstrap-datetimepicker-widget table td.second:hover {
    background-color: #c62232;
    color: #fff;
}

.card.bg-rri .bootstrap-datetimepicker-widget table td.today::before,
.card.bg-gradient-danger .bootstrap-datetimepicker-widget table td.today::before {
    border-bottom-color: #fff;
}

.card.bg-rri .bootstrap-datetimepicker-widget table td.active,
.card.bg-rri .bootstrap-datetimepicker-widget table td.active:hover,
.card.bg-gradient-danger .bootstrap-datetimepicker-widget table td.active,
.card.bg-gradient-danger .bootstrap-datetimepicker-widget table td.active:hover {
    background-color: #e4606d;
    color: #fff;
}
            </style>