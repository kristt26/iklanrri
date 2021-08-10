angular.module('adminctrl', [])
    .controller('pageController', pageController)
    .controller('homeController', homeController)
    .controller('LayananController', LayananController)
    .controller('tarifController', tarifController)
    .controller('pasangIklanController', pasangIklanController)
    .controller('profileController', profileController)
    .controller('UserController', UserController)
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

function tarifController($scope, $http, helperServices, tarifServices, layananServices, message) {
    $scope.$emit("SendUp", "Tarif");
    $scope.datas = [];
    $scope.model = {};
    $scope.layanans = [];
    $scope.layanan = {};
    $scope.simpan = true;
    tarifServices.get().then(res => {
        $scope.datas = res;
        layananServices.get().then(res => {
            $scope.layanans = res;
        })
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
                    $scope.layanan = {};
                    $scope.simpan = true;
                })
            } else {
                tarifServices.post(param).then(res => {
                    message.info("Berhasil");
                    $scope.model = {};
                    $scope.layanan = {};
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
    const groupBy = key => array =>
        array.reduce((objectsByKeyValue, obj) => {
            const value = obj[key];
            objectsByKeyValue[value] = (objectsByKeyValue[value] || []).concat(obj);
            return objectsByKeyValue;
        }, {});
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
        $scope.datas.forEach(element => {
            element.tanggalmulai = new Date(element.tanggalmulai);
            element.tanggalselesai = new Date(element.tanggalselesai);
        });
        console.log($scope.datas);
        
        // const groupByBrand = groupBy('tanggal');
        // var test = groupByBrand($scope.datas[0].jadwalsiaran)
        // console.log(
        //     test
        //   );
        // $("#invoice").modal("show");
    })
    $scope.grouptanggal = (data)=>{
        $scope.total = 0;
        var newArray = [];
        var dataTanggal="";
        data.forEach(element => {
            if(dataTanggal != element.tanggal){
                var item = {tanggal: element.tanggal}
                newArray.push(item);
                dataTanggal=element.tanggal;
            }
        });

        newArray.forEach(element => {
            element.pagi = '-';
            element.siang = '-';
            element.sore = '-';
            var item = data.filter(x=>x.tanggal==element.tanggal);
            item.forEach(element1 => {
                element1.waktu=='Pagi' ? element.pagi = element1.waktu: element1.waktu == 'Siang' ? element.siang=element1.waktu : element1.waktu == 'Sore' ? element.sore = element1.waktu : '-';
            });
            element.panjang = item.length;
            $scope.total += element.panjang;
        });
        return newArray;
    }
    $scope.edit = (item) => {
        $scope.model = angular.copy(item);
        $scope.simpan = false;
    }

    $scope.jadwals = [];
    $scope.total = 0;
    $scope.tampilJadwal = (data)=>{
        $scope.jadwals = $scope.grouptanggal(data.jadwalsiaran);
        $("#jadwalsiaran").modal('show');
        console.log($scope.jadwals);
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
                            $("#invoice").modal("hide");
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
    $scope.lanjut = (set) => {
        var itemharga = $scope.cekHarga($scope.model);
        if(set=='Info'){
            if(itemharga){
                var param = angular.copy($scope.model);
                param.tanggalmulai = param.tanggalmulai.getFullYear() + "-" + (param.tanggalmulai.getMonth() + 1) + "-" + param.tanggalmulai.getDate();
                param.tanggalselesai = param.tanggalselesai.getFullYear() + "-" + (param.tanggalselesai.getMonth() + 1) + "-" + param.tanggalselesai.getDate();
                pasangIklanServices.getJadwal(param).then(res=>{
                    $scope.jadwals = $scope.grouptanggal(res);
                    $scope.tarif.panjang = res.length;
                    $scope.tarif.durasi = itemharga.durasi;
                    $scope.tarif.harga = itemharga.harga;
                    $scope.model.idorder = Date.now();
                    $scope.model.tarifid = itemharga.itemharga.id;
                    $scope.model.biaya = (itemharga.harga * ($scope.tarif.panjang)) + ((itemharga.harga * ($scope.tarif.panjang)) * 0.1);
                    console.log($scope.model);
                    console.log($scope.tarif);
                    if(res.length>0){
                        $("#tarifId").modal("hide");
                        $("#jadwalInfo").modal("show");
                    }else{
                        message.info("Jadwal Siaran Penuh Silahkan Pilih Tanggal Lain");
                        $("#jadwalInfo").modal("hide");
                        $("#tarifId").modal('show');
                    }
                    
                })
            }
        }else{
            $("#jadwalInfo").modal("hide");
            $("#invoice").modal("show");
        }
    }

    $scope.cekHarga = (model) => {
        var lamasiar = model.tanggalselesai.getTime() - model.tanggalmulai.getTime();
        var lamasiar = lamasiar / (1000 * 3600 * 24);
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

    $scope.checkTanggal = (item) => {
        if ($scope.selisihTanggal(item, new Date()) < 1) {
            $scope.model.tanggalmulai = null;
            message.error("Minimal Tanggal pemasangan 1 hari dari tanggal pesan!!!");
        }
        console.log(new Date());
        console.log();
    }

    $scope.selisihTanggal = (tanggal1, tanggal2) => {
        // varibel miliday sebagai pembagi untuk menghasilkan hari
        var miliday = 24 * 60 * 60 * 1000;
        var tglPertama = Date.parse(tanggal1);
        var tglKedua = Date.parse(tanggal2);
        var selisih = (tglPertama - tglKedua) / 1000;
        var selisih = Math.floor(selisih / (86400));
        return selisih + 1;
    }
}

function profileController($scope, $http, helperServices, profileServices, message) {
    $scope.$emit("SendUp", "Layanan");
    $scope.datas = [];
    $scope.model = {};
    profileServices.get().then(res => {
        $scope.datas = res;
        console.log(res);
    })
    $scope.edit = () => {
        $scope.model = angular.copy($scope.datas);
        $("#editProfile").modal('show');
    }
    $scope.save = (param) => {
        message.dialog("Anda yakin ???", "Ya", "Tidak").then(x => {
            profileServices.put(param).then(res => {
                message.info("Berhasil");
                $("#editProfile").modal('hide');
                $scope.model = {};
            })
        })
    }
}

function UserController($scope, $http, helperServices, userServices, message) {
    $scope.$emit("SendUp", "Layanan");
    $scope.datas = [];
    $scope.model = {};
    userServices.get().then(res => {
        $scope.datas = res;
        console.log(res);
    })
    // $scope.edit = () => {
    //     $scope.model = angular.copy($scope.datas);
    //     $("#editProfile").modal('show');
    // }
    // $scope.save = (param) => {
    //     message.dialog("Anda yakin ???", "Ya", "Tidak").then(x => {
    //         profileServices.put(param).then(res => {
    //             message.info("Berhasil");
    //             $("#editProfile").modal('hide');
    //             $scope.model = {};
    //         })
    //     })
    // }
}

