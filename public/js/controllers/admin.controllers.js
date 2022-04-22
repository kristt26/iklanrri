angular.module('adminctrl', [])
    .controller('pageController', pageController)
    .controller('homeController', homeController)
    .controller('LayananController', LayananController)
    .controller('tarifController', tarifController)
    .controller('pasangIklanController', pasangIklanController)
    .controller('profileController', profileController)
    .controller('UserController', UserController)
    .controller('orderController', orderController)
    .controller('jadwalController', jadwalController)
    .controller('laporanIklanController', laporanIklanController)
    .controller('statusBayarController', statusBayarController)
    .controller('iklanTayangController', iklanTayangController)
    .controller('homeGuestController', homeGuestController);

function pageController($scope, helperServices) {
    $scope.Title = "Page Header";
}

function homeController($scope, $http, helperServices, homeServices) {
    $scope.$emit("SendUp", "Home");
    homeServices.get().then(x => {
        console.log(x);
        var lebel = [];
        var datas = [];
        var color = [];
        x.forEach(element => {
            lebel.push($scope.setBulan(element.stringbln));
            datas.push(element.jumlah);
            color.push("#" + Math.floor(Math.random() * 16777215).toString(16));
        });
        console.log(lebel);
        console.log(datas);
        console.log(color);
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: lebel,
                datasets: [{
                    data: datas,
                    backgroundColor: color,
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false,
                        labels: {
                            color: 'rgb(255, 99, 132)'
                        }
                    },
                    title: {
                        display: true,
                        text: 'Pemasangan Iklan Tahun ' + new Date().getFullYear(),
                    }
                }
            }
        });
    })

    $scope.setBulan = (bulan) => {
        switch (parseInt(bulan)) {
            case 1:
                return "Januari"
                break;
            case 2:
                return "Februari"
                break;
            case 3:
                return "Maret"
                break;
            case 4:
                return "April"
                break;
            case 5:
                return "Mei"
                break;
            case 6:
                return "Juni"
                break;
            case 7:
                return "Juli"
                break;
            case 8:
                return "Agustus"
                break;
            case 9:
                return "September"
                break;
            case 10:
                return "Oktober"
                break;
            case 11:
                return "November"
                break;

            default:
                return "Desember"
                break;
        }
    }
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
        console.log(res);
        layananServices.get().then(lay => {
            $scope.layanans = lay;
        })
    })
    $scope.edit = (item) => {
        $scope.model = angular.copy(item);
        $scope.simpan = false;
        $scope.$applyAsync(x => {
            $scope.layanan = $scope.layanans.find(x => x.id == $scope.model.layananid);
            console.log($scope.layanan);
        })
        $("#tarifId").modal("show");
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
    $scope.grouptanggal = (data) => {
        $scope.total = 0;
        var newArray = [];
        var dataTanggal = "";
        data.forEach(element => {
            if (dataTanggal != element.tanggal) {
                var item = { tanggal: element.tanggal }
                newArray.push(item);
                dataTanggal = element.tanggal;
            }
        });

        newArray.forEach(element => {
            element.pagi = '-';
            element.siang = '-';
            element.sore = '-';
            var item = data.filter(x => x.tanggal == element.tanggal);
            item.forEach(element1 => {
                element1.waktu == 'Pagi' ? element.pagi = element1.waktu : element1.waktu == 'Siang' ? element.siang = element1.waktu : element1.waktu == 'Sore' ? element.sore = element1.waktu : '-';
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
    $scope.tampilJadwal = (data) => {
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
                            message.dialogmessage("Pemesanan Iklan Sukses", "OK").then(x => {
                                document.location.reload();
                            });
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
        if (set == 'Info') {
            var param = angular.copy($scope.model);
            param.tanggalmulai = param.tanggalmulai.getFullYear() + "-" + (param.tanggalmulai.getMonth() + 1) + "-" + param.tanggalmulai.getDate();
            param.tanggalselesai = param.tanggalselesai.getFullYear() + "-" + (param.tanggalselesai.getMonth() + 1) + "-" + param.tanggalselesai.getDate();
            pasangIklanServices.getJadwal(param).then(res => {
                var itemharga = $scope.cekHarga($scope.model, res.length);
                $scope.jadwals = $scope.grouptanggal(res);
                $scope.tarif.panjang = res.length;
                $scope.tarif.durasi = itemharga.durasi;
                $scope.tarif.harga = itemharga.harga;
                $scope.model.idorder = Date.now();
                $scope.model.tarifid = itemharga.itemharga.id;
                $scope.model.biaya = (itemharga.harga * ($scope.tarif.panjang)) + ((itemharga.harga * ($scope.tarif.panjang)) * 0.1);
                console.log($scope.model);
                console.log($scope.tarif);
                if (res.length > 0) {
                    $("#tarifId").modal("hide");
                    $("#jadwalInfo").modal("show");
                } else {
                    message.info("Jadwal Siaran Penuh Silahkan Pilih Tanggal Lain");
                    $("#jadwalInfo").modal("hide");
                    $("#tarifId").modal('show');
                }

            })
        } else {
            $("#jadwalInfo").modal("hide");
            $("#invoice").modal("show");
        }
    }

    $scope.cekHarga = (model, lamasiar) => {
        var harga = {};
        var item = $scope.harga.filter(x => x.kategori == $scope.tarif.kategori && x.jenis == $scope.tarif.jenis);
        if (lamasiar > 100) {
            harga = item.find(x => x.uraian == ">100 Kali" || x.uraian == ">101 Kali");
        } else {
            item.forEach(element => {
                var uraian = element.uraian.split(" Spot");
                if (uraian.length > 1) {
                    uraian = uraian[0].split("-");
                    var n1 = parseInt(uraian[0]);
                    var n2 = parseInt(uraian[1]);
                    if (n1 <= lamasiar && n2 >= lamasiar) {
                        harga = element;
                    }
                } else {
                    harga = element;
                }
            });
        }
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

function profileController($scope, $http, helperServices, profileServices, message, AuthService) {
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
    $scope.reset = () => {
        $("#showReset").modal('show');
    }

    $scope.resetPassword = (item) => {
        profileServices.reset(item).then(x => {
            message.dialogmessage("Reset Berhasil Silahkan Login Ulang", "OK").then(x => {
                profileServices.off().then(x => {
                    document.location.reload();
                });
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

function orderController($scope, $http, helperServices, orderServices, message, $sce) {
    $scope.$emit("SendUp", "Layanan");
    $scope.datas = [];
    $scope.model = {};
    orderServices.get().then(res => {
        $scope.datas = res;
        console.log(res);
    })
    $scope.jadwal = (param) => {
        $scope.infoOrder = param;
        $scope.jadwals = $scope.grouptanggal(param.jadwal);
        $("#jadwalsiaran").modal("show");
    }
    $scope.files = "";
    $scope.detailOrder = (item) => {
        $scope.infoOrder = item;
        $scope.$applyAsync(x => {
            $scope.files = $sce.trustAsResourceUrl(helperServices.url + "img/file/" + item.kontent);
        })
        $("#detailOrder").modal("show");
    }

    $scope.print = () => {
        $.LoadingOverlay("show");
        $("#print").printArea();
        setTimeout(() => {
            $.LoadingOverlay("hide");
        }, 1000);
    }

    $scope.grouptanggal = (data) => {
        $scope.total = 0;
        var newArray = [];
        var dataTanggal = "";
        data.forEach(element => {
            if (dataTanggal != element.tanggal) {
                var item = { tanggal: element.tanggal }
                newArray.push(item);
                dataTanggal = element.tanggal;
            }
        });

        newArray.forEach(element => {
            element.pagi = '-';
            element.siang = '-';
            element.sore = '-';
            var item = data.filter(x => x.tanggal == element.tanggal);
            item.forEach(element1 => {
                element1.waktu == 'Pagi' ? element.pagi = element1.waktu : element1.waktu == 'Siang' ? element.siang = element1.waktu : element1.waktu == 'Sore' ? element.sore = element1.waktu : '-';
            });
            element.panjang = item.length;
            $scope.total += element.panjang;
        });
        return newArray;
    }

    $scope.edit = (item) => {
        $scope.model = angular.copy(item);
        $("#editItem").modal("show");
    }

    $scope.save = (item) => {
        message.dialog("Anda yakin ingin memproses Order Iklan??", "OK").then(x => {
            orderServices.put(item).then(res => {
                message.dialogmessage("Proses Berhasil").then(x => {
                    document.location.reload();
                })
            })
        })
    }
}

function jadwalController($scope, helperServices, jadwalServices, message, $sce) {
    $scope.$emit("SendUp", "Layanan");
    $scope.datas = [];
    $scope.spot = [];
    $scope.pengumuman = [];
    $scope.model = {};
    $scope.jenisTanggal = "spot";
    jadwalServices.get().then(res => {
        $scope.datas = res;
        $scope.spot = $scope.datas.filter(x => x.layanan == 'Spot Iklan');
        $scope.pengumuman = $scope.datas.filter(x => x.layanan == 'Pengumuman');
        $scope.iklanspot($scope.grouptanggal($scope.spot));
    })
    $scope.jadwal = (param) => {
        $scope.infoOrder = param;
        $scope.jadwals = $scope.grouptanggal(param.jadwal);
        $("#jadwalsiaran").modal("show");
    }
    $scope.grouptanggal = (data) => {
        $scope.total = 0;
        var newArray = [];
        var dataTanggal = "";
        data.forEach(element => {
            if (dataTanggal != element.tanggal) {
                var item = { tanggal: element.tanggal }
                newArray.push(item);
                dataTanggal = element.tanggal;
            }
        });

        newArray.forEach(element => {
            var item = data.filter(x => x.tanggal == element.tanggal);
            element.display = 'background';
            element.start = element.tanggal
            element.end = element.tanggal
            element.langth = item.length
            element.color = item.length > 0 && item.length < 5 ? 'green' : item.length > 4 && item.length < 10 ? 'yellow' : item.length > 9 && item.length < 15 ? 'orange' : item.length == 15 ? '#dc3545' : ''
            delete element.tanggal;
        });
        return newArray;
    }
    $scope.iklanspot = (datas) => {
        var Calendar = FullCalendar.Calendar;
        var calendarEl = document.getElementById('calendar');
        var items = [];
        var calendar = new Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            locale: 'id',
            themeSystem: 'bootstrap',
            //Random default events
            events: items = datas,
            dateClick: function (info) {
                // alert('Date: ' + info.dateStr);
                // alert('Resource ID: ' + info.resource.id);
                jadwalServices.detail(info.dateStr).then(x => {
                    $scope.dataDetail = x;
                    if (x.spot.length > 0 || x.pengumuman.length > 0) {
                        $("#detail").modal('show');
                    }
                })
            }
        });
        console.log(items);

        calendar.render();
        // $('#calendar').fullCalendar()

        /* ADDING EVENTS */
        var currColor = '#3c8dbc' //Red by default
        // Color chooser button
        $('#color-chooser > li > a').click(function (e) {
            e.preventDefault()
            // Save color
            currColor = $(this).css('color')
            // Add color effect to button
            $('#add-new-event').css({
                'background-color': currColor,
                'border-color': currColor
            })
        })
        $('#add-new-event').click(function (e) {
            e.preventDefault()
            // Get value and make sure it is not null
            var val = $('#new-event').val()
            if (val.length == 0) {
                return
            }

            // Create events
            var event = $('<div />')
            event.css({
                'background-color': currColor,
                'border-color': currColor,
                'color': '#fff'
            }).addClass('external-event')
            event.text(val)
            $('#external-events').prepend(event)

            // Add draggable funtionality
            ini_events(event)

            // Remove event from text input
            $('#new-event').val('')
        })
    }
    $scope.setData = (set) => {
        if (set == 'spot') {
            $scope.iklanspot($scope.grouptanggal($scope.spot));
        } else {
            $scope.iklanspot($scope.grouptanggal($scope.pengumuman));
        }
    }
}

function laporanIklanController($scope, helperServices, laporanServices, message, $sce) {
    $scope.$emit("SendUp", "Layanan");
    $scope.datas = [];
    $scope.spot = [];
    $scope.pengumuman = [];
    $scope.model = {};
    $scope.jenisTanggal = "spot";
    // laporanServices.getIklan().then(res => {
    //     $scope.datas = res;
    //     $scope.spot = $scope.datas.filter(x => x.layanan == 'Spot Iklan');
    //     $scope.pengumuman = $scope.datas.filter(x => x.layanan == 'Pengumuman');
    //     $scope.iklanspot($scope.grouptanggal($scope.spot));
    // })

    setTimeout((x) => {
        $.LoadingOverlay("hide");
    }, 1000);
    $scope.tampil = (item) => {
        $.LoadingOverlay("show");
        var a = item.split(' - ');
        a[0] = new Date(a[0]);
        a[1] = new Date(a[1]);
        if (a[0] !== a[1]) {
            $scope.model.awal = a[0].getFullYear() + '-' + (a[0].getMonth() + 1) + '-' + a[0].getDate();
            $scope.model.akhir = a[1].getFullYear() + '-' + (a[1].getMonth() + 1) + '-' + a[1].getDate();
            laporanServices.get($scope.model).then(x => {
                $scope.datas = x;
                $scope.total = 0;
                x.forEach(element => {
                    $scope.total += parseFloat(element.nominal);
                });
                $.LoadingOverlay("hide");
            })
        }
        $.LoadingOverlay("hide");
    }
    $scope.print = () => {
        $("#print").printArea();
    }
}

function statusBayarController($scope, helperServices, statusBayarServices, message, $sce) {
    $scope.$emit("SendUp", "Layanan");
    $scope.datas = [];
    $scope.spot = [];
    $scope.pengumuman = [];
    $scope.model = {};
    statusBayarServices.get().then(res => {
        $scope.datas = res;
    })
}

function iklanTayangController($scope, $http, helperServices, orderServices, message, $sce) {
    $scope.$emit("SendUp", "Layanan");
    $scope.datas = [];
    $scope.model = {};
    orderServices.getTayang().then(res => {
        $scope.datas = res;
        console.log(res);
    })
    $scope.jadwal = (param) => {
        $scope.infoOrder = param;
        $scope.jadwals = $scope.grouptanggal(param.jadwal);
        $("#jadwalsiaran").modal("show");
    }
    $scope.files = "";
    $scope.detailOrder = (item) => {
        $scope.infoOrder = item;
        $scope.$applyAsync(x => {
            $scope.files = $sce.trustAsResourceUrl(helperServices.url + "img/file/" + item.kontent);
        })
        $("#detailOrder").modal("show");
    }

    $scope.print = () => {
        $.LoadingOverlay("show");
        $("#print").printArea();
        setTimeout(() => {
            $.LoadingOverlay("hide");
        }, 1000);
    }

    $scope.grouptanggal = (data) => {
        $scope.total = 0;
        var newArray = [];
        var dataTanggal = "";
        data.forEach(element => {
            if (dataTanggal != element.tanggal) {
                var item = { tanggal: element.tanggal }
                newArray.push(item);
                dataTanggal = element.tanggal;
            }
        });

        newArray.forEach(element => {
            element.pagi = '-';
            element.siang = '-';
            element.sore = '-';
            var item = data.filter(x => x.tanggal == element.tanggal);
            item.forEach(element1 => {
                element1.waktu == 'Pagi' ? element.pagi = element1.waktu : element1.waktu == 'Siang' ? element.siang = element1.waktu : element1.waktu == 'Sore' ? element.sore = element1.waktu : '-';
            });
            element.panjang = item.length;
            $scope.total += element.panjang;
        });
        return newArray;
    }
}

function homeGuestController($scope, helperServices, homeGuestServices, message, $sce) {
    $scope.$emit("SendUp", "Layanan");
    $scope.datas = [];
    $scope.model = {};
    homeGuestServices.get().then(res => {
        $scope.datas = res;
        $scope.spot = $scope.datas.find(x => x.layanan == 'Spot Iklan');
        $scope.pengumuman = $scope.datas.find(x => x.layanan == 'Pengumuman');
        console.log($scope.pengumuman);
        $scope.spotNonPrime = $scope.spot.kategori[0].dataKategori[0].data;
        $scope.spotNonReguler = $scope.spot.kategori[0].dataKategori[1].data;
        $scope.spotKomPrime = $scope.spot.kategori[1].dataKategori[0].data;
        $scope.spotKomReguler = $scope.spot.kategori[1].dataKategori[1].data;
        $scope.pengNon = $scope.pengumuman.kategori[0].dataKategori;
        $scope.pengKom = $scope.pengumuman.kategori[1].dataKategori;
    })
}