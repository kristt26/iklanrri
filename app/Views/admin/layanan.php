<div class="row" ng-controller="LayananController">
  <div class="col-md-4">
    <div class="card card-rri">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-plus-square fa-1x" ></i>&nbsp;&nbsp; Data Layanan</h3>
      </div>
      <div class="card-body">
        <form role="form" ng-submit="save(model)">
          <div class="form-group">
            <label for="layanan" class="col-form-label col-form-label-sm">Layanan</label>
            <input type="text" class="form-control  form-control-sm" id="layanan" ng-model="model.layanan" placeholder="Nama Layanan" required>
          </div>
          <div class="form-group">
            <label for="status" class="col-form-label col-form-label-sm">Status</label>
            <select id="status" class="form-control form-control-sm" ng-model="model.status" required>
                <option value="1">Aktif</option>
                <option value="0">Tidak Aktif</option>
            </select>
          </div>
          <div class="form-group d-flex justify-content-end">
            <button type="submit" class="btn btn-primary btn-sm pull-right">{{simpan ? 'Simpan': 'Ubah'}}</button>
            <button type="button" ng-show="!simpan" class="btn btn-warning btn-sm pull-right" ng-click="clear()">Clear</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="card card-rri">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-th-list"></i>&nbsp;&nbsp; Data Layanan</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body table-responsive p-0" style="height: 200px;">
        <table class="table table-sm table-hover table-head-fixed text-nowrap">
          <thead>
            <tr>
              <th>No</th>
              <th>Layanan</th>
              <th>Status</th>
              <th><i class="fas fa-cog"></i></th>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="item in datas">
              <td>{{$index+1}}</td>
              <td>{{item.layanan}}</td>
              <td>{{item.status=='1' ? 'Aktif' : 'Tidak Aktif'}}</td>
              <td>
                <button type="button" class="btn btn-warning btn-sm" ng-click ="edit(item)"><i class="fas fa-edit"></i></button>
                <button type="button" class="btn btn-rri btn-sm" ng-click ="delete(item)"><i class="fas fa-trash"></i></button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- /.card-body -->
    </div>
  </div>
</div>
