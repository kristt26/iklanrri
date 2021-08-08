<div class="row" ng-controller="tarifController">
    <div class="col-md-12">
        <div class="card card-danger">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-th-list"></i>&nbsp;&nbsp; Data Tarif</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#tarifId">
                        Tambah
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div id="accordion">
                    <div class="card" ng-repeat="item in datas">
                        <div class="card-header">
                            <h4 class="card-title w-100">
                                <a class="d-block w-100" data-toggle="collapse"
                                    data-target="#collapselayanan{{item.id}}">
                                    <strong>{{item.layanan}}</strong>
                                </a>
                            </h4>
                        </div>
                        <div id="collapselayanan{{item.id}}" class="collapse {{$index=='0' ? 'show':''}}"
                            data-parent="#accordion">
                            <div class="card-body">
                                <div id="accordionkategori">
                                    <div class="card" ng-repeat="kategori in item.kategori">
                                        <div class="card-header">
                                            <h4 class="card-title w-100">
                                                <a class="d-block w-100" data-toggle="collapse"
                                                    data-target="#collapsekategori{{item.id}}{{kategori.id}}">
                                                    <span style="color: #00a8e6;">{{kategori.kategori}}</span>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapsekategori{{item.id}}{{kategori.id}}"
                                            class="collapse {{$index=='0' ? 'show':''}}"
                                            data-parent="#accordionkategori">
                                            <div class="card-body">
                                                <div class="table-responsive p-0" style="height: 200px;">
                                                    <table
                                                        class="table table-sm table-hover table-head-fixed text-nowrap">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th ng-if="item.layanan !== 'Pengumuman'">Jenis</th>
                                                                <th>Uraian</th>
                                                                <th>Satuan</th>
                                                                <th>Tarif</th>
                                                                <th><i class="fas fa-cog"></i></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr ng-repeat="tariff in kategori.tarif">
                                                                <td>{{$index+1}}</td>
                                                                <td ng-if="item.layanan !== 'Pengumuman'">{{tariff.jenis}}</td>
                                                                <td>{{tariff.uraian}}</td>
                                                                <td>{{tariff.satuan}}</td>
                                                                <td>{{tariff.tarif}}</td>
                                                                <td>
                                                                    <button type="button" class="btn btn-warning btn-sm"
                                                                        ng-click="edit(item)"><i
                                                                            class="fas fa-edit"></i></button>
                                                                    <button type="button" class="btn btn-danger btn-sm"
                                                                        ng-click="delete(item)"><i
                                                                            class="fas fa-trash"></i></button>
                                                                </td>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="tarifId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form role="form" ng-submit="save(model)">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title">Tambah Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="layanan" class="col-form-label col-form-label-sm">Layanan</label>
                            <select id="layanan" class="form-control form-control-sm select2" ng-options="item as item.layanan for item in layanans"
                                ng-model="layanan" required ng-change="model.layananid = layanan.id"></select>
                        </div>
                        <div class="form-group" ng-if="layanan">
                            <label for="kategori" class="col-form-label col-form-label-sm">Kategori</label>
                            <select id="kategori" class="form-control form-control-sm" ng-model="model.kategori"
                                required>
                                <option value="">---Pilih---</option>
                                <option value="Non Komersial">Non Komersial</option>
                                <option value="Komersial">Komersial</option>
                            </select>
                        </div>
                        <div class="form-group" ng-if="layanan.layanan != 'Pengumuman' && model.kategori">
                            <label for="jenis" class="col-form-label col-form-label-sm">Jenis</label>
                            <select id="jenis" class="form-control form-control-sm" ng-model="model.jenis" required>
                                <option value="">---Pilih---</option>
                                <option value="Prime Time">Prime Time</option>
                                <option value="Reguler Time">Reguler Time</option>
                            </select>
                        </div>
                        <div class="form-group" ng-if="model.kategori">
                            <label for="uraian" class="col-form-label col-form-label-sm">Uraian</label>
                            <input type="text" class="form-control  form-control-sm" id="uraian" ng-model="model.uraian"
                                placeholder="Nama uraian" required>
                        </div>
                        <div class="form-group" ng-if="model.kategori">
                            <label for="satuan" class="col-form-label col-form-label-sm">Satuan</label>
                            <input type="text" class="form-control  form-control-sm" id="satuan" ng-model="model.satuan"
                                placeholder="Nama satuan" required>
                        </div>
                        <div class="form-group" ng-if="model.kategori">
                            <label for="tarif" class="col-form-label col-form-label-sm">Tarif</label>
                            <input type="text" class="form-control  form-control-sm" id="tarif" ng-model="model.tarif"
                                placeholder="Nama tarif" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit"
                            class="btn btn-primary btn-sm pull-right">{{simpan ? 'Simpan': 'Ubah'}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>