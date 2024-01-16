<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Система мониторинга</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <style>
        #map {
            width: 100%;
            height: 400px;
        }

        .overlay-filter {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            mix-blend-mode: difference;
            pointer-events: none;
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
        <h4 class="text-white text-center mt-3">Приветствуем вас нашей компании – вашем
            надежном партнере в мире
            недвижимости!
            Мы понимаем, что покупка или продажа недвижимости
            - это важное решение, которое требует внимания к деталям
            и профессионального подхода. Наша компания основана на
            принципах доверия, ответственности и высокого качества
            обслуживания, чтобы сделать процесс сделки максимально комфортным
            и прозрачным для наших клиентов.</h4>
    </header>
    <main class="container mb-5">
        <div class="overlay-filter">
            <div id="map" class="inverted-map"></div>
        </div>
        <script>
            function generateRandomLocations(count, label) {
                var locations = [];
                for (var i = 0; i < count; i++) {
                    locations.push({
                        latitude: 55.7558 + (Math.random() - 0.7) * 20,
                        longitude: 37.6176 + (Math.random() - 0.8) * 20,
                        label: label
                    });
                }
                return locations;
            }

            var locationsGroup1 = generateRandomLocations(10000, '1');
            var locationsGroup2 = generateRandomLocations(10000, '2');
            var locationsGroup3 = generateRandomLocations(10000, '3');

            ymaps.ready(init);

            function init() {
                var myMap = new ymaps.Map('map', {
                    center: [55.7558, 37.6176], // Initial center 
                    zoom: 10
                });

                // Create ObjectManager for each group with clustering options and custom layout 
                var objectManagerGroup1 = createObjectManager(locationsGroup1, '#ff0000');
                var objectManagerGroup2 = createObjectManager(locationsGroup2, '#00ff00');
                var objectManagerGroup3 = createObjectManager(locationsGroup3, '#0000ff');

                // Add ObjectManagers to the map 
                myMap.geoObjects.add(objectManagerGroup1);
                myMap.geoObjects.add(objectManagerGroup2);
                myMap.geoObjects.add(objectManagerGroup3);
            }

            function createObjectManager(locations, clusterColor) {
                var objectManager = new ymaps.ObjectManager({
                    clusterize: true,
                    gridSize: 32,
                    clusterIconLayout: 'default#pieChart',
                    clusterIconPieChartRadius: 25,
                    clusterIconPieChartCoreRadius: 15,
                    clusterIconPieChartStrokeWidth: 3,
                    geoObjectOpenBalloonOnClick: true
                });

                // Set the same color for individual markers and cluster icons 
                var markerOptions = {
                    preset: 'islands#circleDotIcon',
                    iconColor: clusterColor
                };

                var features = locations.map(function(location, index) {
                    return {
                        type: 'Feature',
                        id: index,
                        geometry: {
                            type: 'Point',
                            coordinates: [location.latitude, location.longitude]
                        },
                        properties: {
                            iconContent: '1',
                            label: location.label,
                            hintContent: 'Fire Type: ' + getRandomFireType() + '<br>Date: ' + getRandomDate()
                        },
                        options: markerOptions
                    };
                });

                objectManager.add({
                    type: 'FeatureCollection',
                    features: features
                });

                return objectManager;
            }

            function getRandomFireType() {
                var fireTypes = ['Forest Fire', 'House Fire', 'Industrial Fire', 'Wildfire'];
                return fireTypes[Math.floor(Math.random() * fireTypes.length)];
            }

            function getRandomDate() {
                var startDate = new Date(2020, 0, 1);
                var endDate = new Date();
                var randomDate = new Date(startDate.getTime() + Math.random() * (endDate.getTime() - startDate.getTime()));
                return randomDate.toLocaleDateString();
            }
        </script>
    </main>
    <footer class="mt-auto text-bg-primary">

    </footer>
</body>

</html>