angular.module('ctrl', [])
    .controller('pageController', pageController)
    .controller('homeController', homeController)
    .controller('pasangIklanController', pasangIklanController);

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

function wisataController($scope, $http, helperServices, wisataServices) {
    $scope.datas = [];
    wisataServices.get().then(res => {
        $scope.datas = res;
    })

}