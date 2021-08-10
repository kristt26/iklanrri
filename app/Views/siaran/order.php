<div class="row" ng-controller="LayananController">
    <div class="col-md-12">
        <div class="card card-rri">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-th-list"></i>&nbsp;&nbsp; Data Order</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#tarifId">
                        Tambah
                    </button>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table datatable="ng" class="table table-sm table-hover table-head-fixed text-nowrap">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th>Layanan</th>
                            <th>Status</th>
                            <th style="width: 10%;"><i class="fas fa-cog"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="item in datas">
                            <td>{{$index+1}}</td>
                            <td>{{item.layanan}}</td>
                            <td>{{item.status=='1' ? 'Aktif' : 'Tidak Aktif'}}</td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" ng-click="edit(item)"><i
                                        class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-rri btn-sm" ng-click="delete(item)"><i
                                        class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>