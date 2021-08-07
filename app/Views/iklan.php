<div class="row" ng-controller="pasangIklanController">
    <div class="col-md-12">
        <div class="card card-danger">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-th-list"></i>&nbsp;&nbsp; Histori Pemasangan</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#tarifId">
                        Tambah
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive p-0" style="height: 200px;">
                    <table class="table table-sm table-hover table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Jenis</th>
                                <th>Uraian</th>
                                <th>Satuan</th>
                                <th>Tarif</th>
                                <th><i class="fas fa-cog"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="tariff in item.tarif">
                                <td>{{$index+1}}</td>
                                <td>{{tariff.jenis}}</td>
                                <td>{{tariff.uraian}}</td>
                                <td>{{tariff.satuan}}</td>
                                <td>{{tariff.tarif}}</td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" ng-click="edit(item)"><i
                                            class="fas fa-edit"></i></button>
                                    <button type="button" class="btn btn-danger btn-sm" ng-click="delete(item)"><i
                                            class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="tarifId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
        data-backdrop="false" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title">Pasang Iklan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="layanan" class="col-form-label col-form-label-sm">Layanan Iklan</label>
                        <select id="layanan" class="form-control form-control-sm"
                            ng-options="item as item.layanan for item in layanans" ng-model="layanan" required
                            ng-change="model.layananid = layanan.id">
                            <option value="">---Pilih Layanan---</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="topik" class="col-form-label col-form-label-sm">Topik</label>
                        <input type="text" class="form-control  form-control-sm" id="topik" ng-model="model.topik"
                            placeholder="Topik Iklan" required>
                    </div>
                    <div class="form-group">
                        <label for="waktu" class="col-form-label col-form-label-sm">Waktu Siaran</label>
                        <select id="waktu" class="form-control form-control-sm select2" ng-model="model.waktu" required
                            multiple="multiple" data-placeholder="---Pilih Waktu Siaran---">
                            <option value="Pagi">Pagi</option>
                            <option value="Siang">Siang</option>
                            <option value="Sore">Sore</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tanggal" class="col-form-label col-form-label-sm">Lama Siaran</label>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <input type="date" class="form-control form-control-sm" id="tanggalmulai"
                                    ng-model="model.tanggalmulai" placeholder="tanggalmulai" required>
                            </div>
                            <label for="tanggalselesai"
                                class="col-sm-1 col-form-label col-form-label-sm text-center">s/d</label>
                            <div class="col-sm-3">
                                <input type="date" class="form-control  form-control-sm" id="tanggalselesai"
                                    ng-model="model.tanggalselesai" placeholder="tanggalselesai" required>
                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="jeniskontent" class="col-form-label col-form-label-sm">Jenis Kontent</label>
                        <select id="jeniskontent" class="form-control form-control-sm select2" ng-model="model.jeniskontent"
                            required data-placeholder="---Pilih Jenis Konten---" style="width: 30%;">
                            <option value="Text">Text</option>
                            <option value="File">File</option>
                        </select>
                    </div>
                    <div class="form-group" ng-if="model.jeniskontent=='Text'">
                        <label for="text" class="col-form-label col-form-label-sm">Kontent Text</label>
                        <input type="text" class="form-control form-control-sm" id="text" ng-model="model.kontent"
                            placeholder="Nama uraian" required>
                    </div>
                    <div class="form-group" ng-if="model.jeniskontent=='File'">
                        <label for="foto" class="col-form-label col-form-label-sm">Kontent File</label>
                        <div class="input-group mb-3">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input custom-file-input-sm" id="foto"
                                    aria-describedby="inputGroupFileAddon01" ng-model="model.kontent"
                                    base-sixty-four-input ng-change="cekFile(model.kontent)">
                                <label class="custom-file-label"
                                    for="foto">{{model.kontent ? model.kontent.filename: model.foto && !model.kontent ? model.foto: 'Pilih File'}}</label>
                            </div>
                            <span ng-show="form.model.kontent.$error.maxsize">Files must not exceed 5000 KB</span>
                        </div>
                        <small id="foto" class="form-text text-muted" ng-if="model.foto || model.kontent">
                            ​<picture ng-if="model.foto && !model.kontent">
                                <source srcset="<?=base_url('public/img/galeri/{{model.foto}}')?>">
                                <img src="<?=base_url('public/img/galeri/{{model.foto}}')?>"
                                    class="img-fluid img-thumbnail" alt="..." width="35%">
                            </picture>
                            ​<picture ng-if="model.kontent">
                                <source>
                                <img data-ng-src="data:{{model.kontent.filetype}};base64,{{model.kontent.base64}}"
                                    class="img-fluid img-thumbnail" alt="..." width="35%">
                            </picture>
                        </small>
                        <!-- <div class="col-sm-10">
                            </div> -->
                    </div>
                    <div class="form-group">
                        <label for="kategori" class="col-form-label col-form-label-sm">Kategori</label>
                        <select id="kategori" class="form-control form-control-sm select2" ng-model="tarif.kategori"
                            required data-placeholder="---Pilih Kategori Iklan---">
                            <option value="Non Komersial">Non Komersial</option>
                            <option value="Komersial">Komersial</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jenistarif" class="col-form-label col-form-label-sm">Jenis</label>
                        <select id="jenistarif" class="form-control form-control-sm select2" ng-model="tarif.jenis"
                            required data-placeholder="---Pilih Jenis Iklan---">
                            <option value="Prime Time">Prime Time</option>
                            <option value="Reguler Time">Reguler Time</option>
                        </select>
                    </div>
                </div>
                <div class="">
                    <div class="d-flex">
                        <div class="mr-auto p-2"><button type="button" class="btn btn-secondary btn-sm"
                                ng-click="batal()">Batal</button></div>
                        <div class="p-2"><button type="submit" class="btn btn-primary btn-sm"
                                ng-click="lanjut()">Lanjut</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="invoice" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
        data-backdrop="false" data-keyboard="false">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="invoice p-3 mb-3">
                        <div class="row">
                            <div class="col-12">
                                <h4>
                                    <i class="fas fa-globe"></i> RRI NUSANTARA 1 JAYAPURA
                                    <small class="float-right">Date: <?= date('d M Y');?></small>
                                </h4>
                            </div>
                        </div>
                        <div class="row invoice-info">
                            <div class="col-sm-4 invoice-col">
                                From
                                <address>
                                    <strong>RRI Jayapura</strong><br>
                                    Hamadi, Jayapura Selatan, Kota Jayapura, Papua<br>
                                    Indonesia, Kode Pos 99221<br>
                                    Phone: (0967) 536386<br>
                                    Email: info@rrijayapura.com
                                </address>
                            </div>
                            <div class="col-sm-4 invoice-col">
                                To
                                <address>
                                    <strong><?= session()->get("first_name").' '.session()->get("last_name");?></strong><br>
                                    <?= session()->get("alamat") ? session()->get("alamat") : '' ;?><br>
                                    Phone: <?= session()->get("kontak");?><br>
                                    Email: <?= session()->get("email");?>
                                </address>
                            </div>
                            <div class="col-sm-4 invoice-col">
                                <br>
                                <b>Order ID:</b> {{model.idorder}}<br>
                                <b>Payment Due:</b> <?= date('d-m-Y', strtotime(' +1 day'))?><br>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Kategori</th>
                                            <th>Tanggal Tayang</th>
                                            <th>Lama Tayang</th>
                                            <th>Waktu Tayang</th>
                                            <th>Durasi</th>
                                            <th>Harga Satuan</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{tarif.kategori + ' ' + tarif.jenis}}</td>
                                            <td>{{model.tanggalmulai | date: 'd MMM y'}} s/d
                                                {{model.tanggalselesai | date: 'd MMM y'}}</td>
                                            <td>{{tarif.durasi}}</td>
                                            <td>{{model.waktu.length}}</td>
                                            <td>{{model.waktu.length * tarif.durasi}}</td>
                                            <td>{{tarif.harga | currency}}</td>
                                            <td>{{tarif.harga * (model.waktu.length * tarif.durasi) | currency}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <p class="lead">Payment Methods:</p>
                                <img src="../../dist/img/credit/visa.png" alt="Visa">
                                <img src="../../dist/img/credit/mastercard.png" alt="Mastercard">
                                <img src="../../dist/img/credit/american-express.png" alt="American Express">
                                <img src="../../dist/img/credit/paypal2.png" alt="Paypal">

                                <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                                    Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya
                                    handango imeem
                                    plugg
                                    dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
                                </p>
                            </div>
                            <div class="col-6">
                                <p class="lead">Amount Due <?= date('d-m-Y', strtotime(' +1 day'))?></p>

                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th style="width:50%">Subtotal:</th>
                                            <td>{{tarif.harga * (model.waktu.length * tarif.durasi) | currency}}</td>
                                        </tr>
                                        <tr>
                                            <th>Tax (10%)</th>
                                            <td>{{(tarif.harga * (model.waktu.length * tarif.durasi)) * 0.1 | currency}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Total:</th>
                                            <td>{{(tarif.harga * (model.waktu.length * tarif.durasi)) + ((tarif.harga * (model.waktu.length * tarif.durasi)) * 0.1) | currency}}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row no-print">
                            <div class="col-12">
                                <a href="invoice-print.html" rel="noopener" target="_blank" class="btn btn-default"><i
                                        class="fas fa-print"></i> Print</a>
                                <button id="pay-button" type="button" class="btn btn-success float-right" ng-click="save()"><i
                                        class="far fa-credit-card"></i>
                                    Bayar
                                </button>
                                <button type="button" class="btn btn-secondary float-right" ng-click="batal()"
                                    style="margin-right: 5px;">
                                    <i class="fas fa-back"></i> Batal
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>