@extends('layout.index')
@section('header')
    <style>
        #map {
            height: 480px;
            width: 100%;
            /* background: rgb(69, 69, 69); */
        }
    </style>
    {{--
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCd7jO393kLkq5Y5d9B16CBk4IapupNX8U&callback=initMap"></script>
    <script>
        function initMap() {
            var options = {
                center: new google.maps.LatLng(-7.5, 110),
                zoom: 8
            }
            map = new google.maps.Map(document.getElementById('map'), options);
            console.log(map);
        }
        // function init() {
        //     var options = {
        //         center: new google.maps.LatLng(-7.5, 110),
        //         zoom: 12,
        //         mapTypeId: google.maps.MapTypeId.ROADMAP,
        //     };
        //     var map = new google.maps.Map(document.getElementById('map'), options);
        // }
        // google.maps.event.addDomListener(window, 'load', init);
    </script> --}}


    {{-- <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script> --}}


    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    {{-- <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.79/dist/L.Control.Locate.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.79/dist/L.Control.Locate.min.js" charset="utf-8">
    </script> --}}
@endsection
@section('container')
    <h1>Google</h1>
    <div id="map"></div>




    <script src="https://unpkg.com/leaflet@1.2.0/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    <script>
        var mks = L.latLng(-5.135399, 119.423790);
        var toko = L.latLng(-5.1581334, 119.4174849);
        // var mksW = new L.Routing.Waypoint(mks);
        var tokoW = new L.Routing.Waypoint(toko);
        var map = L.map('map').setView(mks, 15);
        var centerMap = true;

        var redIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        var blueIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });


        setInterval(() => {

            geoLocate();
        }, 10000);

        function geoLocate() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            }
        }

        function showPosition(position) {
            let myPos = L.latLng(position.coords.latitude, position.coords.longitude);
            let myPosW = new L.Routing.Waypoint(myPos);


            let myLocMarker = L.marker(myPos, {
                icon: blueIcon,
                title: "My Location",
            }).addTo(map);
            myLocMarker.bindPopup("My Location");

            let tokoLocMarker = L.marker(toko, {
                icon: redIcon,
                title: "Toko Location",
            }).addTo(map).bindPopup("Toko is Here");


            // console.log(map);
            // var x = L.Routing.control({
            //     waypoints: [
            //         L.latLng(-5.1370991, 119.476484),
            //         L.latLng(-5.182699, 119.4410681),
            //     ]
            // }).addTo(map);
            // console.log(x);

            let routeUs = L.Routing.osrmv1();
            let t = new L.Routing.Waypoint(L.latLng(-5.182699, 119.4410681));
            let k = new L.Routing.Waypoint(L.latLng(-5.1370991, 119.476484));

            routeUs.route([myPosW, tokoW], (err, routes) => {
                if (!err) {
                    let best = 10000000000000000;
                    let bestRoute = 0;
                    console.log(routes);

                    for (i in routes) {
                        if (routes[i].summary.totalDistance < best) {
                            best = routes[i].summary.totalDistance;
                            bestRoute = i;
                        }
                    }
                    L.Routing.line(routes[bestRoute], {
                        styles: [{
                            color: 'cyan',
                            weight: '5',
                        }]
                    }).addTo(map);
                    let jarak = (routes[bestRoute].summary.totalDistance > 1000) ? (routes[bestRoute].summary
                            .totalDistance / 1000).toFixed(2) + " km" : routes[bestRoute].summary.totalDistance +
                        " meter";
                    tokoLocMarker.bindPopup(
                        "Toko bla bla bla <br> Jarak: " + jarak
                    );
                }
            });
            if(centerMap){
                map.setView(toko);
                centerMap = !centerMap;
            }

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);
        }
    </script>
@endsection
