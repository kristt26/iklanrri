<div class="row" ng-controller="iklanTayangController">
    <div class="col-md-12">
        <div class="card card-rri">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-th-list"></i>&nbsp;&nbsp; Iklan Tayang</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table datatable="ng" class="table table-sm table-hover table-head-fixed text-nowrap">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th>Order ID</th>
                            <th>Layanan</th>
                            <th>Tanggal Order</th>
                            <th>Pemesan</th>
                            <th>Status</th>
                            <th style="width: 10%;"><i class="fas fa-cog"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="item in datas">
                            <td>{{$index+1}}</td>
                            <td>{{item.orderid}}</td>
                            <td>{{item.layanan}}</td>
                            <td>{{item.tanggal | date}}</td>
                            <td>{{item.fullname}}</td>
                            <td>{{item.status=='1' ? 'Aktif' : 'Tidak Aktif'}}</td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm" ng-click="jadwal(item)"><i
                                        class="fas fa-calendar"></i></button>
                                <button type="button" class="btn btn-primary btn-sm" ng-click="detailOrder(item)"><i
                                        class="fas fa-eye"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="jadwalsiaran" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true" data-backdrop="false" data-keyboard="false">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="table-responsive p-0" style="height: 500px;">
                        <div id="print">
                            <div class="screen">
                                <div class="col-md-12 d-flex justify-content-center">
                                    <img src="<?=base_url('img/RRI.png');?>" width="10%">
                                </div>
                                <h4>BUKTI PENYIARAN IKLAN</h4>
                                <table>
                                    <tr>
                                        <td>Jenis Siaran</td>
                                        <td>:&nbsp;</td>
                                        <td>{{infoOrder.layanan}} {{infoOrder.topik}}</td>
                                    </tr>
                                    <tr>
                                        <td>Klien</td>
                                        <td>:&nbsp;</td>
                                        <td>{{infoOrder.fullname}}</td>
                                    </tr>
                                    <tr>
                                        <td>Periode Penyiaran</td>
                                        <td>:&nbsp;</td>
                                        <td>Tanggal {{infoOrder.tanggalmulai | date: 'd MMMM y'}} s/d
                                            {{infoOrder.tanggalselesai | date: 'd MMMM y'}}</td>
                                    </tr>
                                    <tr>
                                        <td>Frekuensi Penyiaran</td>
                                        <td>:&nbsp;</td>
                                        <td>{{total}} Kali</td>
                                    </tr>
                                    <tr>
                                        <td>Disiarkan Melalui</td>
                                        <td>:&nbsp;</td>
                                        <td>Programan 1 (Satu) RRI Jayapura</td>
                                    </tr>
                                    <tr>
                                        <td>Frekuensi</td>
                                        <td>:&nbsp;</td>
                                        <td>96.0, 97.6, 93.5 & Fm 100.0 Mhz</td>
                                    </tr>
                                </table>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Pagi</th>
                                        <th>Siang</th>
                                        <th>Sore</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="item in jadwals">
                                        <td>{{$index+1}}</td>
                                        <td>{{item.tanggal}}</td>
                                        <td>{{item.pagi}}</td>
                                        <td>{{item.siang}}</td>
                                        <td>{{item.sore}}</td>
                                        <td>{{item.panjang}}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5">Total</td>
                                        <td>{{total}} Kali</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="mr-auto p-2"> <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">Close</button></div>
                    <button type="button" class="btn btn-success float-right" ng-click="print()">Print</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="detailOrder" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true" data-backdrop="false" data-keyboard="false">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <h4><strong>Info Iklan</strong></h4>
                                <div class="form-group row">
                                    <label for="layanan" class="col-sm-4 col-form-label">Layanan</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly class="form-control-plaintext" id="layanan"
                                            ng-model="infoOrder.layanan">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="topik" class="col-sm-4 col-form-label">Topik</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly class="form-control-plaintext" id="topik"
                                            ng-model="infoOrder.topik">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="klien" class="col-sm-4 col-form-label">Klien</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly class="form-control-plaintext" id="klien"
                                            ng-model="infoOrder.fullname">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tayang" class="col-sm-4 col-form-label">Tayang</label>
                                    <div class="col-sm-8">
                                        <ul>
                                            <li ng-repeat="item in infoOrder.waktu">{{item}}</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="periode" class="col-sm-4 col-form-label">Periode Siaran</label>
                                    <div class="col-sm-8">
                                        <label for="periode" class="col-form-label">{{infoOrder.tanggalmulai}} s/d
                                            {{infoOrder.tanggalselesai}}</label>
                                    </div>
                                </div>
                                <div class="form-group" ng-if="infoOrder.jeniskontent=='File'">
                                    <label for="kontentfile" class="col-sm-4 col-form-label">File Contenr</label>
                                    <div class="col-sm-8">
                                        <div data-ng-if="files">
                                            <audio ng-src="{{ files }}" controls="controls" autobuffer></audio>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row" ng-if="infoOrder.jeniskontent=='Text'">
                                    <label for="kontenttext" class="col-sm-4 col-form-label">Periode Siaran</label>
                                    <div class="col-sm-8">
                                        <textarea id="kontenttext" readonly class="form-control-plaintext" cols="5"
                                            ng-model="infoOrder.kontent"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4><strong>Info Pembayaran</strong></h4>
                                <div class="form-group row">
                                    <label for="orderid" class="col-sm-4 col-form-label">Id Order</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly class="form-control-plaintext" id="orderid"
                                            ng-model="infoOrder.orderid">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="frekuensi" class="col-sm-4 col-form-label">Frekuensi Siaran</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly class="form-control-plaintext" id="frekuensi"
                                            ng-model="infoOrder.jadwal.length">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="satuan" class="col-sm-4 col-form-label">Harga Satuan</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly class="form-control-plaintext" id="satuan"
                                            ng-model="infoOrder.tarif | currency">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="nominal" class="col-sm-4 col-form-label">Total Bayar</label>
                                    <div class="col-sm-8">
                                        <input type="text" readonly class="form-control-plaintext" id="nominal"
                                            ng-model="infoOrder.nominal | currency">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="statusbayar" class="col-sm-4 col-form-label">Total Bayar</label>
                                    <div class="col-sm-8">
                                        {{infoOrder.statusbayar == 'Success' ? 'Lunas' : 'Pending'}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="mr-auto p-2"> <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">Close</button></div>
                </div>
            </div>
        </div>
    </div>
</div>