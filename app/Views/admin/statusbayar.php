<div class="row" ng-controller="statusBayarController">
  <div class="col-md-12">
    <div class="card card-rri">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-th-list"></i>&nbsp;&nbsp; Status Pembayaran</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body table-responsive p-0">
        <table class="table table-sm table-hover table-bordered text-nowrap">
          <thead>
            <tr class="text-center align-middle">
              <th>No</th>
              <th>Layanan</th>
              <th>Kategori</th>
              <th>Customer</th>
              <th>Nominal <br>(Rp.)</th>
              <th>Status Bayar</th>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="item in datas">
              <td>{{$index+1}}</td>
              <td>{{item.layanan}}</td>
              <td>{{item.kategori}}</td>
              <td>{{item.fullname}}</td>
              <td class="text-right">{{item.nominal | currency}}</td>
              <td>{{item.statusbayar}}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- /.card-body -->
    </div>
  </div>
</div>
