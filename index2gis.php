<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Система мониторинга</title>
    <script src="https://maps.api.2gis.ru/2.0/loader.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />
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

        .inverted-map .leaflet-tile-pane {
            filter: invert(100%);
            -ms-filter: invert(100%);
            -webkit-filter: invert(100%);
            -moz-filter: invert(100%);
            -o-filter: invert(100%);
        }

        .custom-cluster-icon {
            background-color: #4CAF50;
            border-radius: 50%;
            color: #fff;
            text-align: center;
            line-height: 40px;
            width: 40px;
            height: 40px;
        }

        .cluster-label-1 {
            background-color: #4CAF50;
        }

        .cluster-label-2 {
            background-color: #2196F3;
        }

        .cluster-label-3 {
            background-color: #FFC107;
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
        <h4 class="text-white text-center mt-3">Приветствуем вас нашей компании – вашем надежном партнере в мире
            недвижимости!
            Мы понимаем, что покупка или продажа недвижимости
            - это важное решение, которое требует внимания к деталям
            и профессионального подхода. Наша компания основана на
            принципах доверия, ответственности и высокого качества
            обслуживания, чтобы сделать процесс сделки максимально комфортным
            и прозрачным для наших клиентов.</h4>
    </header>
    <main class="container mb-5">
        <div id="map" class="inverted-map"></div>

        <script>
            function generateRandomLocations(count) {
                var locations = [];
                for (var i = 0; i < count; i++) {
                    locations.push({
                        latitude: 54.98 + (Math.random() - 0.5) * 0.1,
                        longitude: 82.89 + (Math.random() - 0.5) * 0.1,
                        label: 'cluster' + (Math.floor(Math.random() * 3) + 1) // Random label (cluster1, cluster2, cluster3)
                    });
                }
                return locations;
            }

            var locationsFromDatabase = generateRandomLocations(100);

            DG.then(function () {
                var map = DG.map('map', {
                    center: [54.98, 82.89],
                    zoom: 10
                });

                var markers = locationsFromDatabase.map(function (location) {
                    return DG.marker([location.latitude, location.longitude], { label: location.label }).addTo(map);
                });

                var clusterGroup = DG.markerClusterGroup({
                    iconCreateFunction: function (cluster) {
                        var childMarkers = cluster.getAllChildMarkers();
                        var labels = {};
                        for (var i = 0; i < childMarkers.length; i++) {
                            var label = childMarkers[i].options.label;
                            labels[label] = true;
                        }
                        var clusterIcon = DG.divIcon({
                            html: '<div class="custom-cluster-icon cluster-label-' + Object.keys(labels).join(' cluster-label-') + '">' + Object.keys(labels).join(', ') + '</div>',
                            className: 'custom-cluster',
                            iconSize: [40, 40]
                        });
                        return clusterIcon;
                    }
                });

                clusterGroup.addLayers(markers);
                map.addLayer(clusterGroup);
            });
        </script>
    </main>
    <footer class="mt-auto text-bg-primary">

    </footer>
</body>

</html>
