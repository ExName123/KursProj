<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Система мониторинга</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <style>
        #map {
            width: 100%;
            height: 400px;
        }

        .inverted-map #map {
            filter: invert(100%);
        }

        .leaflet-control-attribution.leaflet-control {
            display: none;
        }
    </style>
</head>

<body style="background-image: url(images/background.jpg);" class="container-fluid">
    <header class="container rounded-start rounded-end bg-primary shadow p-3 mt-5 mb-5 rounded">
        <div class="d-flex align-items-center">
            <img style="width: 3em; height: 3em;" class="rounded-pill" src="images/logo.jpg">
            <h1 class="text-white ms-3 mb-0">Сервис по мониторингу пожаров</h1>
            <div class="d-flex text-white ms-auto">
                <h6 class="mb-0">Авторизация</h6>
                <h6 class="ms-3 mb-0">Регистрация</h6>
            </div>
        </div>
        <hr class="my-2">
    </header>
    <main class="container mb-5">
        <div id="map" class="inverted-map"></div>

        <script>
            function generateRandomLocations(count) {
                var locations = [];
                for (var i = 0; i < count; i++) {
                    locations.push({
                        latitude: 55.7558 + (Math.random() - 0.5) * 10,
                        longitude: 37.6176 + (Math.random() - 0.5) * 10
                    });
                }
                return locations;
            }

            var locationsFromDatabase = generateRandomLocations(10000);

            var myMap = L.map('map').setView([55.7558, 37.6176], 10);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: ''
            }).addTo(myMap);

            var markers = L.markerClusterGroup();

            locationsFromDatabase.forEach(function (location, index) {
                var marker = L.marker(new L.LatLng(location.latitude, location.longitude));
                markers.addLayer(marker);
            });

            myMap.addLayer(markers);

            myMap.attributionControl.addAttribution('© OpenStreetMap contributors');
        </script>
    </main>
    <footer class="mt-auto text-bg-primary">

    </footer>
</body>

</html>
