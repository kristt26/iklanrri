<div ng-controller="homeController">
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>
                        <?=$monthiklan?>
                    </h3>

                    <p>Iklan Bulan ini</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?=$totaliklan?></h3>

                    <p>Iklan Keseluruhan</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3><?='Rp. ' . number_format($monthpendapatan, 2, ',', '.')?></h3>

                    <p>Pemasukan Bulan ini</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3><?='Rp. ' . number_format($totalpendapatan, 2, ',', '.')?></h3>

                    <p>Pemasukan Keseluruhan</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="card" style="width: 100%">
        <div class="card-body">
            <canvas id="myChart" width="400" height="100"></canvas>
        </div>
    </div>

</div>

<script src="../../libs/chart.js/dist/chart.js"></script>
<script>

</script>