angular.module('adminctrl', [])
    .controller('pageController', pageController)
    .controller('homeController', homeController)
    .controller('LayananController', LayananController)
    .controller('tarifController', tarifController)
    .controller('pasangIklanController', pasangIklanController)
    ;

function pageController($scope, helperServices) {
    $scope.Title = "Page Header";
}

function homeController($scope, $http, helperServices) {
    $scope.$emit("SendUp", "Home");
}

function LayananController($scope, $http, helperServices, layananServices, message) {
    $scope.$emit("SendUp", "Layanan");
    $scope.datas = [];
    $scope.model = {};
    $scope.simpan = true;
    layananServices.get().then(res => {
        $scope.datas = res;
    })
    $scope.edit = (item) => {
        $scope.model = angular.copy(item);
        $scope.simpan = false;
    }
    $scope.save = (param) => {
        message.dialog("Anda yakin ???", "Ya", "Tidak").then(x => {
            if (param.id) {
                layananServices.put(param).then(res => {
                    message.info("Berhasil");
                    $scope.model = {};
                    $scope.simpan = true;
                })
            } else {
                layananServices.post(param).then(res => {
                    message.info("Berhasil");
                    $scope.model = {};
                    $scope.simpan = true;
                })
            }
        })
    }
    $scope.delete = (param) => {
        message.dialog("Anda Yakin", "Ya", "Tidak").then(x => {
            layananServices.deleted(param).then(res => {
                message.info("Berhasil");
            })
        })
    }
}

function tarifController($scope, $http, helperServices, tarifServices, message) {
    $scope.$emit("SendUp", "Tarif");
    $scope.datas = [];
    $scope.model = {};
    $scope.simpan = true;
    tarifServices.get().then(res => {
        $scope.datas = res;
        console.log(res);
    })
    $scope.edit = (item) => {
        $scope.model = angular.copy(item);
        $scope.simpan = false;
    }
    $scope.save = (param) => {
        message.dialog("Anda yakin ???", "Ya", "Tidak").then(x => {
            if (param.id) {
                tarifServices.put(param).then(res => {
                    message.info("Berhasil");
                    $scope.model = {};
                    $scope.simpan = true;
                })
            } else {
                tarifServices.post(param).then(res => {
                    message.info("Berhasil");
                    $scope.model = {};
                    $scope.simpan = true;
                })
            }
            $("#tarifId").modal('hide');
        })
    }
    $scope.delete = (param) => {
        message.dialog("Anda Yakin", "Ya", "Tidak").then(x => {
            tarifServices.deleted(param).then(res => {
                message.info("Berhasil");
            })
        })
    }
}

function pasangIklanController($scope, $http, helperServices, pasangIklanServices, message) {
    $scope.$emit("SendUp", "Pemasangan Iklan");
    $scope.datas = [];
    $scope.layanans = [];
    $scope.model = {};
    $scope.tarif = {};
    $scope.harga = [];
    $scope.simpan = true;
    pasangIklanServices.get().then(res => {
        $scope.layanans = res.layanan;
        $scope.datas = res.iklan;
        $scope.harga = res.tarif;
        // $("#invoice").modal("show");
    })
    $scope.edit = (item) => {
        $scope.model = angular.copy(item);
        $scope.simpan = false;
    }
    $scope.save = () => {
        var param = angular.copy($scope.model);
        param.tanggalmulai = param.tanggalmulai.getFullYear() + "-" + (param.tanggalmulai.getMonth() + 1) + "-" + param.tanggalmulai.getDate();
        param.tanggalselesai = param.tanggalselesai.getFullYear() + "-" + (param.tanggalselesai.getMonth() + 1) + "-" + param.tanggalselesai.getDate();
        message.dialog("Anda yakin ???", "Ya", "Tidak").then(x => {
            pasangIklanServices.post(param).then(data => {
                console.log('token = ' + data);
                var resultType = document.getElementById('result-type');
                var resultData = document.getElementById('result-data');
                function changeResult(type, data) {
                    $("#result-type").val(type);
                    $("#result-data").val(JSON.stringify(data));
                }
                snap.pay(data, {
                    onSuccess: function (result) {
                        changeResult('success', result);
                        console.log(result.status_message);
                        console.log(result);
                        $("#payment-form").submit();
                    },
                    onPending: function (result) {
                        console.log(result.status_message);
                        pasangIklanServices.cekStatus(result).then(res => {
                            message.info("Pemesanan Iklan Sukses");
                            $("#invoice").modal("show");
                        })
                    },
                    onError: function (result) {
                        changeResult('error', result);
                        console.log(result.status_message);
                        $("#payment-form").submit();
                    }
                });
            })
            $("#tarifId").modal('hide');
        })
    }
    $scope.delete = (param) => {
        message.dialog("Anda Yakin", "Ya", "Tidak").then(x => {
            pasangIklanServices.deleted(param).then(res => {
                message.info("Berhasil");
            })
        })
    }
    $scope.cekFile = (item) => {
        console.log(item);
    }
    $scope.lanjut = () => {
        var itemharga = $scope.cekHarga($scope.model);
        $scope.tarif.durasi = itemharga.durasi;
        $scope.tarif.harga = itemharga.harga;
        $scope.model.idorder = Date.now();
        $scope.model.tarifid = itemharga.itemharga.id;
        $scope.model.biaya = (itemharga.harga * (itemharga.durasi * $scope.model.waktu.length)) + ((itemharga.harga * (itemharga.durasi * $scope.model.waktu.length)) * 0.1);
        console.log($scope.model);
        console.log($scope.tarif);
        $("#tarifId").modal("hide");
        $("#invoice").modal("show");
    }

    $scope.cekHarga = (model) => {
        var lamasiar = model.tanggalselesai.getTime() - model.tanggalmulai.getTime();
        var lamasiar = lamasiar / (1000 * 3600 * 24) * model.waktu.length;
        var harga = {};
        var item = $scope.harga.filter(x => x.kategori == $scope.tarif.kategori && x.jenis == $scope.tarif.jenis);
        item.forEach(element => {
            var uraian = element.uraian.split(" Spot");
            uraian = uraian[0].split("-");
            if (parseInt(uraian[0]) < (lamasiar * $scope.model.waktu.length) && parseInt(uraian[1]) >= (lamasiar * $scope.model.waktu.length)) {
                harga = element;
            }
        });
        return { harga: parseFloat(harga.tarif), durasi: lamasiar, itemharga: harga };
    }

    $scope.batal = () => {
        $scope.tarif = {};
        $scope.model = {};
        $("#tarifId").modal("hide");
        $("#invoice").modal("hide");
    }
}

