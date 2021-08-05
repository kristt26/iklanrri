angular.module('adminctrl', [])
    .controller('pageController', pageController)
    .controller('homeController', homeController)
    .controller('wisataController', wisataController)
    .controller('tambahWisataController', tambahWisataController)
    .controller('umkmController', umkmController)
    .controller('tambahUmkmController', tambahUmkmController)
    .controller('usersController', usersController)
    .controller('wilayahController', wilayahController)
    .controller('kategoriController', kategoriController)
    .controller('galeryController', galeryController);

function pageController($scope, helperServices) {
    $scope.Title = "Page Header";
}

function homeController($scope, $http, helperServices) {
    $scope.$emit("SendUp", "Home");
}

function wisataController($scope, $http, helperServices, wisataServices) {
    $scope.$emit("SendUp", "Wisata");
    $scope.datas = [];
    wisataServices.get().then(res => {
        $scope.datas = res;
    })

}

function tambahWisataController($scope, $http, helperServices, wilayahServices, message, wisataServices, kategoriServices) {
    $scope.$emit("SendUp", "Wisata");
    $scope.model = {};
    $scope.tampilinput = false;
    $scope.kecamatans = [];
    $scope.kecamatan = {};
    $scope.kelurahan = {};
    $scope.kategoris = [];
    $scope.kategori = {};
    var url = new URLSearchParams(window.location.search);

    const apiKey = "AAPKe04c23b9e1e14827a485e3c06a91369a44TROFRCnx1O_wzKtYcV81b7ApPKaBQwXH064LgF6Y-BxD_3-5yyVhpmIrVi1V19";

    const basemapEnum = "ArcGIS:Navigation";
    mapboxgl.accessToken = 'pk.eyJ1Ijoia3Jpc3R0MjYiLCJhIjoiY2txcWt6dHgyMTcxMzMwc3RydGFzYnM1cyJ9.FJYE8uVi-eVl_mH_DLLEmw';
    var map = new mapboxgl.Map({
        container: 'mapp',
        style: 'mapbox://styles/mapbox/streets-v11',
        // style: `https://basemaps-api.arcgis.com/arcgis/rest/services/styles/${basemapEnum}?type=style&token=${apiKey}`,
        center: [131.25478, -0.86210],
        zoom: 10
    });

    // var marker = new mapboxgl.Marker({
    //     color: "maroon",                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
    //     draggable: true
    // }).setLngLat([131.25478, -0.86210])
    //     .addTo(map);
    map.on('click', (e) => {
        const coords = e.lngLat.toArray();

        const authentication = new arcgisRest.ApiKey({
            key: apiKey
        });

        arcgisRest
            .reverseGeocode(coords, {
                authentication
            })
            .then((result) => {

                const lngLat = [result.location.x, result.location.y];
                // const label = `${result.address.LongLabel}<br>${lngLat[0].toLocaleString()}, ${lngLat[1].toLocaleString()}`;
                // new mapboxgl.Popup().setLngLat(lngLat).setHTML(label).addTo(map);
                $scope.$applyAsync(() => {
                    $scope.model.alamat = result.address.LongLabel;
                    $scope.model.longitude = lngLat[1];
                    $scope.model.latitude = lngLat[0];
                })
                console.log(result.address);
                $("#addLatLong").modal('hide');
            }).catch((error) => {
                alert("There was a problem using the reverse geocode service. See the console for details.");
                console.error(error);
            });
    })

    map.on('load', function() {
        map.addSource('maine', {
            'type': 'geojson',
            'data': helperServices.url + 'public/js/sorong.geojson'
        });

        map.addLayer({
            'id': 'maine',
            'type': 'fill',
            'source': 'maine',
            'layout': {},
            'paint': {
                'fill-color': '#0080ff',
                'fill-opacity': 0.2
            }
        });
        map.addLayer({
            'id': 'outline',
            'type': 'line',
            'source': 'maine',
            'layout': {},
            'paint': {
                'line-color': 'red',
                'line-width': 1,
                'line-opacity': 0.25
            }
        });
    });

    $scope.showMap = () => {
        $("#addLatLong").modal('show');
        setTimeout(() => {
            map.resize();
        }, 300);
    }
    var geocoder = new MapboxGeocoder({
        accessToken: mapboxgl.accessToken,
        mapboxgl: mapboxgl
    });
    document.getElementById('geocoder').appendChild(geocoder.onAdd(map));
    wilayahServices.get().then(res => {
        $.LoadingOverlay("show");
        res.kecamatans.forEach(element => {
            element.kelurahans = res.kelurahans.filter(x => x.kecamatanid == element.id);
        });
        $scope.kecamatans = res.kecamatans;
        kategoriServices.get().then(kategori => {
            $scope.kategoris = kategori;
            if (url.get('id')) {
                wisataServices.getId(url.get('id')).then(res => {
                    $scope.$applyAsync(() => {
                        res.latitude = parseFloat(res.latitude);
                        res.longitude = parseFloat(res.longitude);
                        $scope.kecamatan = $scope.kecamatans.find(x => x.id == res.kecamatanid);
                        $scope.kelurahan = $scope.kecamatan.kelurahans.find(x => x.id == res.kelurahanid);
                        $scope.model = res;
                        $scope.kategori = $scope.kategoris.find(x => x.id == res.kategoriid);
                        tinymce.get("deskripsi").setContent(res.deskripsi);
                        console.log($scope.model);
                    })
                    $.LoadingOverlay("hide");
                })
            } else {
                $.LoadingOverlay("hide");
            }
        });
    })
    $scope.save = (item) => {
        item.type = "Wisata";
        item.deskripsi = tinymce.get("deskripsi").getContent();
        message.dialogmessage('Anda Yakin?', 'Ya', 'Tidak').then(x => {
            if (item.id) {
                wisataServices.put(item).then(x => {
                    message.confirm("Berhasil Menyimpan Data", true).then(x => {
                        document.location.href = helperServices.url + "admin/wisata";
                    });
                })
            } else {
                wisataServices.post(item).then(x => {
                    message.confirm("Berhasil Mengubah Data", true).then(x => {
                        document.location.href = helperServices.url + "admin/wisata";
                    });
                })
            }
        })
    }
}

function umkmController($scope, $http, helperServices, umkmServices) {
    $scope.$emit("SendUp", "UMKM");
    $scope.datas = [];
    umkmServices.get().then(res => {
        $scope.datas = res;
    })

}

function tambahUmkmController($scope, $http, helperServices, wilayahServices, message, umkmServices, wisataServices) {
    $scope.$emit("SendUp", "UMKM");
    $scope.model = {};
    $scope.tampilinput = false;
    $scope.kecamatans = [];
    $scope.kecamatan = {};

    var url = new URLSearchParams(window.location.search);
    const apiKey = "AAPKe04c23b9e1e14827a485e3c06a91369a44TROFRCnx1O_wzKtYcV81b7ApPKaBQwXH064LgF6Y-BxD_3-5yyVhpmIrVi1V19";

    const basemapEnum = "ArcGIS:Navigation";
    mapboxgl.accessToken = 'pk.eyJ1Ijoia3Jpc3R0MjYiLCJhIjoiY2txcWt6dHgyMTcxMzMwc3RydGFzYnM1cyJ9.FJYE8uVi-eVl_mH_DLLEmw';
    var map = new mapboxgl.Map({
        container: 'mapp',
        style: 'mapbox://styles/mapbox/streets-v11',
        // style: `https://basemaps-api.arcgis.com/arcgis/rest/services/styles/${basemapEnum}?type=style&token=${apiKey}`,
        center: [131.25478, -0.86210],
        zoom: 10
    });

    // var marker = new mapboxgl.Marker({
    //     color: "maroon",                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
    //     draggable: true
    // }).setLngLat([131.25478, -0.86210])
    //     .addTo(map);
    map.on('click', (e) => {
        const coords = e.lngLat.toArray();

        const authentication = new arcgisRest.ApiKey({
            key: apiKey
        });

        arcgisRest
            .reverseGeocode(coords, {
                authentication
            })
            .then((result) => {

                const lngLat = [result.location.x, result.location.y];
                // const label = `${result.address.LongLabel}<br>${lngLat[0].toLocaleString()}, ${lngLat[1].toLocaleString()}`;
                // new mapboxgl.Popup().setLngLat(lngLat).setHTML(label).addTo(map);
                $scope.$applyAsync(() => {
                    $scope.model.alamat = result.address.LongLabel;
                    $scope.model.longitude = lngLat[1];
                    $scope.model.latitude = lngLat[0];
                })
                console.log(result.address);
                $("#addLatLong").modal('hide');
            }).catch((error) => {
                alert("There was a problem using the reverse geocode service. See the console for details.");
                console.error(error);
            });
    })

    map.on('load', function() {
        map.addSource('maine', {
            'type': 'geojson',
            'data': helperServices.url + 'public/js/sorong.geojson'
        });

        map.addLayer({
            'id': 'maine',
            'type': 'fill',
            'source': 'maine',
            'layout': {},
            'paint': {
                'fill-color': '#0080ff',
                'fill-opacity': 0.2
            }
        });
        map.addLayer({
            'id': 'outline',
            'type': 'line',
            'source': 'maine',
            'layout': {},
            'paint': {
                'line-color': 'red',
                'line-width': 1,
                'line-opacity': 0.25
            }
        });
    });

    $scope.showMap = () => {
        $("#addLatLong").modal('show');
        setTimeout(() => {
            map.resize();
        }, 300);
    }
    var geocoder = new MapboxGeocoder({
        accessToken: mapboxgl.accessToken,
        mapboxgl: mapboxgl
    });
    document.getElementById('geocoder').appendChild(geocoder.onAdd(map));
    wilayahServices.get().then(res => {
        res.kecamatans.forEach(element => {
            element.kelurahans = res.kelurahans.filter(x => x.kecamatanid == element.id);
        });
        $scope.kecamatans = res.kecamatans;
        if (url.get('id')) {
            wisataServices.getId(url.get('id')).then(res => {
                $scope.$applyAsync(() => {
                    res.latitude = parseFloat(res.latitude);
                    res.longitude = parseFloat(res.longitude);
                    $scope.kecamatan = $scope.kecamatans.find(x => x.id == res.kecamatanid);
                    $scope.kelurahan = $scope.kecamatan.kelurahans.find(x => x.id == res.kelurahanid);
                    $scope.model = res;
                    tinymce.get("deskripsi").setContent(res.deskripsi);
                    console.log($scope.model);
                })
            })
        }
    })
    $scope.save = (item) => {
        item.type = "UMKM";
        item.deskripsi = tinymce.get("deskripsi").getContent();
        message.dialogmessage('Anda Yakin?', 'Ya', 'Tidak').then(x => {
            if (item.id) {
                umkmServices.put(item).then(x => {
                    message.confirm("Berhasil Menyimpan Data", "Ok").then(x => {
                        document.location.href = helperServices.url + "admin/umkm";
                    });
                })
            } else {
                umkmServices.post(item).then(x => {
                    message.confirm("Berhasil Menyimpan Data", "Ok").then(x => {
                        document.location.href = helperServices.url + "admin/umkm";
                    });
                })
            }
        })
    }
}

function usersController($scope, $http, helperServices, usersServices) {
    $scope.$emit("SendUp", "User");
    $scope.datas = [];
    usersServices.get().then(res => {
        $scope.datas = res;
    })

}

function wilayahController($scope, $http, helperServices, wilayahServices, message) {
    $scope.$emit("SendUp", "Wilayah");
    $scope.datas = [];
    $scope.model = {};
    $scope.model1 = {};
    $scope.itemKelurahan = [];
    $scope.showKelurahan = false;
    wilayahServices.get().then(res => {
        res.kecamatans.forEach(element => {
            element.kelurahans = res.kelurahans.filter(x => x.kecamatanid == element.id);
        });
        $scope.datas = res.kecamatans;
        // console.log($scope.datas);
    })
    $scope.setKelurahan = (item, id) => {
        $scope.itemKelurahan = item;
        $scope.model1.kecamatanid = id;
        $scope.showKelurahan = true;
    }
    $scope.save = (item, set) => {
        message.dialogmessage('Anda Yakin?', 'Ya', 'Tidak').then(x => {
            if (set == "kecamatan") {
                if (item.id) {
                    wilayahServices.put(item).then(res => {
                        var data = $scope.datas.find(x => x.id == res.id);
                        data.nama = res.nama;
                        $scope.model = {};
                    });
                } else {
                    wilayahServices.post(item).then(res => {
                        $scope.datas.push(angular.copy(res));
                        $scope.model = {};
                    });
                }
            } else {
                if (item.id) {
                    wilayahServices.putKelurahan(item).then(res => {
                        var dataKecamatan = $scope.datas.find(x => x.id == res.kecamatanid);
                        if (dataKecamatan) {
                            var dataKelurahan = dataKecamatan.find(x => x.id == res.id);
                            if (dataKelurahan) {
                                dataKelurahan.nama = res.nama
                                $scope.model = {};
                            }
                        }
                    });
                } else {
                    wilayahServices.postKelurahan(item).then(res => {
                        var data = $scope.datas.find(x => x.id == res.kecamatanid);
                        if (data) {
                            data.kelurahans.push(angular.copy(res));
                            $scope.model = {};
                        }
                    });
                }
            }
        })

    }
    $scope.edit = (item, set) => {
        if (set == 'kecamatan')
            $scope.model = angular.copy(item);
        else
            $scope.model1 = angular.copy(item);

    }
    $scope.clear = () => {
        $scope.model = {};
        $scope.model1 = {};
        $scope.showKelurahan = false;
    }
}

function galeryController($scope, $http, helperServices, galeryServices, message) {
    $scope.$emit("SendUp", "Galeri");
    var url = new URLSearchParams(window.location.search);
    console.log(url.get('id'));
    $scope.datas = [];
    $scope.model = {};
    $scope.model1 = {};
    $scope.itemKelurahan = [];
    $scope.showKelurahan = false;
    galeryServices.get(url.get('id')).then(res => {
        $scope.datas = res;
        console.log(res);
    })

    $scope.cekFile = (item) => {
        console.log(item);
    }

    $scope.save = (item) => {
        item.lokasiid = $scope.datas.lokasi.id;
        message.dialogmessage('Anda Yakin?', 'Ya', 'Tidak').then(x => {
            galeryServices.post(item).then(res => {
                $scope.model = {};
                message.info("Proses Berhasil");
            });
        })
    }

    // $scope.edit = (item, set) => {
    //     if (set == 'kecamatan')
    //         $scope.model = angular.copy(item);
    //     else
    //         $scope.model1 = angular.copy(item);

    // }
    // $scope.clear = ()=>{
    //     $scope.model = {};
    //     $scope.model1 = {};
    //     $scope.showKelurahan = false;
    // }
}

function kategoriController($scope, $http, helperServices, kategoriServices, message) {
    $scope.$emit("SendUp", "Kategori");
    $scope.datas = [];
    $scope.model = {};

    kategoriServices.get().then(res => {
        $scope.datas = res;
        console.log(res);
    })
    $scope.edit = (item) => {
        $scope.model = angular.copy(item);
    }

    $scope.save = (item) => {
        if ($scope.model.id) {
            message.dialogmessage('Anda Yakin?', 'Ya', 'Tidak').then(x => {
                kategoriServices.put(item).then(res => {
                    $scope.model = {};
                    message.info("Proses Berhasil");
                });
            })
        } else {
            message.dialogmessage('Anda Yakin?', 'Ya', 'Tidak').then(x => {
                kategoriServices.post(item).then(res => {
                    $scope.model = {};
                    message.info("Proses Berhasil");
                });
            })
        }
    }
}