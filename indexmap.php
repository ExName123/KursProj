<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaflet GeoJSON Example</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        #map {
            height: 600px;
        }
    </style>
</head>

<body>
    <div id="map"></div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var mymap = L.map('map').setView([55.7558, 37.6176], 4); // Центр России

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: ''
        }).addTo(mymap);

        // Загружаем данные из файла regions.js с использованием jQuery
        $.getJSON('ru-regions.js', function(data) {
            // Добавление GeoJSON на карту с использованием динамического стиля
            L.geoJson(data, {
                style: function(feature) {
                    return {
                        fillColor: getColor(feature.properties.name),
                        weight: 2,
                        opacity: 1,
                        color: 'white',
                        dashArray: '3',
                        fillOpacity: 0.7
                    };
                }
            }).addTo(mymap);
        });

        // Функция для определения цвета в зависимости от имени региона
        function getColor(name) {
            console.log(name);
            // Добавьте свои регионы и цвета
            if (name === 'Республика Адыгея (Адыгея)') {
                return 'red';
            } else if (name === 'Еще какой-то регион') {
                return 'blue';
            } else {
                return 'green'; // Цвет по умолчанию
            }
        }
    </script>
</body>

</html>
