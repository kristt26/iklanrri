<div ng-controller="homeGuestController">
    <div class="card">
        <div class="card-body">
            <h4 class="text-center">DAFTAR TARIF BIAYA SPOT IKLAN</h4>
            <div class="col md-12">
                <div class="row border">
                    <div class="col-md-6">
                        <h5 class="text-center">DAFTAR HARGA NON KOMERSIAL (PEMDA, LSM & DUNIA PENDIDIKAN)</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <caption class="text-center"><strong>Prime Time</strong></caption>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Uraian</th>
                                        <th>Satuan</th>
                                        <th>Tarif</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="item in spotNonPrime">
                                        <td>{{$index+1}}</td>
                                        <td>{{item.uraian}}</td>
                                        <td>{{item.satuan}}</td>
                                        <td>{{item.tarif}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <caption class="text-center"><strong>Reguler Time</strong></caption>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Uraian</th>
                                        <th>Satuan</th>
                                        <th>Tarif</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="item in spotNonReguler">
                                        <td>{{$index+1}}</td>
                                        <td>{{item.uraian}}</td>
                                        <td>{{item.satuan}}</td>
                                        <td>{{item.tarif}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-center">DAFTAR HARGA KOMERSIAL (SWASTA)</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <caption class="text-center"><strong>Prime Time</strong></caption>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Uraian</th>
                                        <th>Satuan</th>
                                        <th>Tarif</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="item in spotKomPrime">
                                        <td>{{$index+1}}</td>
                                        <td>{{item.uraian}}</td>
                                        <td>{{item.satuan}}</td>
                                        <td>{{item.tarif}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <caption class="text-center"><strong>Reguler Time</strong></caption>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Uraian</th>
                                        <th>Satuan</th>
                                        <th>Tarif</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="item in spotKomReguler">
                                        <td>{{$index+1}}</td>
                                        <td>{{item.uraian}}</td>
                                        <td>{{item.satuan}}</td>
                                        <td>{{item.tarif}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="text-center">DAFTAR TARIF BIAYA Pengumuman</h4>
            <div class="col md-12">
                <div class="row border">
                    <div class="col-md-6">
                        <h5 class="text-center">DAFTAR HARGA NON KOMERSIAL (PEMDA, LSM & DUNIA PENDIDIKAN)</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Uraian</th>
                                        <th>Satuan</th>
                                        <th>Tarif</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="item in pengNon">
                                        <td>{{$index+1}}</td>
                                        <td>{{item.uraian}}</td>
                                        <td>{{item.satuan}}</td>
                                        <td>{{item.tarif}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-center">DAFTAR HARGA KOMERSIAL (SWASTA)</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Uraian</th>
                                        <th>Satuan</th>
                                        <th>Tarif</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="item in pengKom">
                                        <td>{{$index+1}}</td>
                                        <td>{{item.uraian}}</td>
                                        <td>{{item.satuan}}</td>
                                        <td>{{item.tarif}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>