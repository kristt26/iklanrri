angular.module('ctrl', [])
    .controller('pageController', pageController)
    .controller('homeController', homeController)
    .controller('wisataController', wisataController)
    .controller('umkmController', umkmController);

function pageController($scope, helperServices) {
    $scope.Title = "Page Header";
}

function homeController($scope, $http, helperServices) {
    $scope.tampilinput = false;
    mapboxgl.accessToken = 'pk.eyJ1Ijoia3Jpc3R0MjYiLCJhIjoiY2txcWt6dHgyMTcxMzMwc3RydGFzYnM1cyJ9.FJYE8uVi-eVl_mH_DLLEmw';
    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v11',
        center: [131.25478, -0.86210],
        zoom: 10
    });
    var userLocation = new mapboxgl.GeolocateControl({
        positionOptions: {
            enableHighAccuracy: true
        },
        trackUserLocation: true
    });
    map.addControl(userLocation);
    let directions = new MapboxDirections({
        accessToken: mapboxgl.accessToken,
        unit: 'metric',
        profile: 'mapbox/cycling',
        interactive: true,
        steps: true,
        instructions: true,
        language: 'id',
        controls: {
            inputs: false,
            instructions: true,
            profileSwitcher: false
        }


    });

    map.addControl(directions, 'top-left');
    // directions.setOrigin([cord.coords.longitude, cord.coords.latitude]);
    userLocation.on('geolocate', function (a) {
        directions.setOrigin([a.coords.longitude, a.coords.latitude]);
        console.log(a)
    });

    var marker = new mapboxgl.Marker({
        color: "maroon",
        draggable: true
    }).setLngLat([131.25478, -0.86210])
        .addTo(map);

    map.on('load', function () {
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
    // navigator.geolocation.getCurrentPosition((cord) => {


    // }, err => {

    // }, {})
}

// function wisataController($scope, $http, helperServices, wisataServices) {
//     $scope.datas = [];
//     wisataServices.get().then(res => {
//         $scope.datas = res;
//     })

// }

function wisataController($scope, $http, helperServices, message, wisataServices) {
    $scope.model = {};
    $scope.tampilinput = false;
    $scope.kecamatans = [];
    $scope.kecamatan = {};
    $scope.kelurahan = {};
    $scope.cekdirection = false;
    var url = new URLSearchParams(window.location.search);

    const apiKey = "AAPKe04c23b9e1e14827a485e3c06a91369a44TROFRCnx1O_wzKtYcV81b7ApPKaBQwXH064LgF6Y-BxD_3-5yyVhpmIrVi1V19";

    const basemapEnum = "ArcGIS:Navigation";
    mapboxgl.accessToken = 'pk.eyJ1Ijoia3Jpc3R0MjYiLCJhIjoiY2txcWt6dHgyMTcxMzMwc3RydGFzYnM1cyJ9.FJYE8uVi-eVl_mH_DLLEmw';
    var map = new mapboxgl.Map({
        container: 'mapp',
        style: 'mapbox://styles/mapbox/streets-v11',
        // style: `https://basemaps-api.arcgis.com/arcgis/rest/services/styles/${basemapEnum}?type=style&token=${apiKey}`,
        center: [131.25478, -0.86210],
        zoom: 11
    });

    var userLocation = new mapboxgl.GeolocateControl({
        positionOptions: {
            enableHighAccuracy: true
        },
        trackUserLocation: true
    });

    map.addControl(userLocation);
    let directions = new MapboxDirections({
        accessToken: mapboxgl.accessToken,
        unit: 'metric',
        profile: 'mapbox/cycling',
        interactive: true,
        steps: true,
        instructions: true,
        language: 'id',
        controls: {
            inputs: false,
            instructions: true,
            profileSwitcher: false
        }
    });

    

    userLocation.on('geolocate', function (a) {
        directions.setOrigin([a.coords.longitude, a.coords.latitude]);
        console.log(a)
    });



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

    $scope.setDirection = () => {
        if($scope.cekdirection){
            map.addControl(directions, 'top-left');
        }else{
            map.removeControl(directions);
        }
    }

    map.on('load', function () {
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
    
    var geocoder = new MapboxGeocoder({
        accessToken: mapboxgl.accessToken,
        mapboxgl: mapboxgl
    });
    // document.getElementById('geocoder').appendChild(geocoder.onAdd(map));
    wisataServices.get().then(res => {
        var geojson = {};
        geojson.features = [];
        res.forEach(element => {
            var setData = {
                type: 'Feature',
                geometry: {
                    type: 'Point',
                    coordinates: [parseFloat(element.latitude), parseFloat(element.longitude)]
                },
                properties: {
                    title: element.nama,
                    img: element.foto,
                    address: element.alamat,
                    kategori: element.kategori,
                    warna: element.warna
                    // description: '1714 14th St NW, Washington DC',
                    // image: 'https://farm9.staticflickr.com/8604/15769066303_3e4dcce464_n.jpg',
                    // icon: {
                    //     iconUrl: 'https://docs.mapbox.com/mapbox.js/assets/images/astronaut1.png',
                    //     iconSize: [50, 50], // size of the icon
                    //     iconAnchor: [25, 25], // point of the icon which will correspond to marker's location
                    //     popupAnchor: [0, -25], // point from which the popup should open relative to the iconAnchor
                    //     className: 'dot'
                    // }
                }
            };
            geojson.features.push(angular.copy(setData));
        });
        geojson.features.forEach(function (marker) {
            var foto = helperServices.url + "public/img/galeri/" + marker.properties.img;
            // create a HTML element for each feature
            var el = document.createElement('div');
            el.className = 'marker';


            // make a marker for each feature and add it to the map
            var a = new mapboxgl.Marker({ "color": marker.properties.warna })
                .setLngLat(marker.geometry.coordinates)
                .setPopup(
                    new mapboxgl.Popup({ offset: 25 }) // add popups
                        .setHTML(
                            '<img src="' + foto + '" width="100%">' +
                            '<h3>' +
                            marker.properties.title +
                            '</h3><p>' +
                            marker.properties.address +
                            '</p>'
                        )
                )
                .addTo(map);
            // a.setHTML('<h5>' + marker.properties.title + '</h5>');
        });
        // var marker = new mapboxgl.Marker({
        //     color: "maroon",
        //     draggable: true,
        //     description: 'Washington, D.C.'
        // }).setLngLat([parseFloat(element.latitude), parseFloat(element.longitude)])
        //     .addTo(map);
        // console.log(res);


    })
}

function umkmController($scope, $http, helperServices, message, umkmServices) {
    $scope.model = {};
    $scope.tampilinput = false;
    $scope.kecamatans = [];
    $scope.kecamatan = {};
    $scope.kelurahan = {};
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

    var userLocation = new mapboxgl.GeolocateControl({
        positionOptions: {
            enableHighAccuracy: true
        },
        trackUserLocation: true
    });

    map.addControl(userLocation);
    let directions = new MapboxDirections({
        accessToken: mapboxgl.accessToken,
        unit: 'metric',
        profile: 'mapbox/cycling',
        interactive: true,
        steps: true,
        instructions: true,
        language: 'id',
        controls: {
            inputs: false,
            instructions: true,
            profileSwitcher: false
        }
    });

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

    map.on('load', function () {
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

    $scope.setDirection = () => {
        if($scope.cekdirection){
            map.addControl(directions, 'top-left');
        }else{
            map.removeControl(directions);
        }
    }

    // var geocoder = new MapboxGeocoder({
    //     accessToken: mapboxgl.accessToken,
    //     mapboxgl: mapboxgl
    // });
    // document.getElementById('geocoder').appendChild(geocoder.onAdd(map));
    umkmServices.get().then(res => {
        var geojson = {};
        geojson.features = [];
        res.forEach(element => {
            var setData = {
                type: 'Feature',
                geometry: {
                    type: 'Point',
                    coordinates: [parseFloat(element.latitude), parseFloat(element.longitude)]
                },
                properties: {
                    title: element.nama,
                    img: element.foto
                    // description: '1714 14th St NW, Washington DC',
                    // image: 'https://farm9.staticflickr.com/8604/15769066303_3e4dcce464_n.jpg',
                    // icon: {
                    //     iconUrl: 'https://docs.mapbox.com/mapbox.js/assets/images/astronaut1.png',
                    //     iconSize: [50, 50], // size of the icon
                    //     iconAnchor: [25, 25], // point of the icon which will correspond to marker's location
                    //     popupAnchor: [0, -25], // point from which the popup should open relative to the iconAnchor
                    //     className: 'dot'
                    // }
                }
            };
            geojson.features.push(angular.copy(setData));
        });
        geojson.features.forEach(function (marker) {
            var foto = helperServices.url + "public/img/galeri/" + marker.properties.img;
            // create a HTML element for each feature
            var el = document.createElement('div');
            el.className = 'marker';


            // make a marker for each feature and add it to the map
            var a = new mapboxgl.Marker({ "color": "#b40219" })
                .setLngLat(marker.geometry.coordinates)
                .setPopup(
                    new mapboxgl.Popup({ offset: 25 }) // add popups
                        .setHTML(
                            '<img src="' + foto + '" width="100%">' +
                            '<h3>' +
                            marker.properties.title +
                            '</h3><p>' +
                            marker.properties.description +
                            '</p>'
                        )
                )
                .addTo(map);
            // a.setHTML('<h5>' + marker.properties.title + '</h5>');
        });
        // var marker = new mapboxgl.Marker({
        //     color: "maroon",
        //     draggable: true,
        //     description: 'Washington, D.C.'
        // }).setLngLat([parseFloat(element.latitude), parseFloat(element.longitude)])
        //     .addTo(map);
        // console.log(res);


    })
    $scope.save = (item) => {
        item.type = "Wisata";
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