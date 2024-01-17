var myMap;
var objectManager;

ymaps.ready(init);
function init() {
    myMap = new ymaps.Map('map', {
        center: [55.7558, 37.6176],
        zoom: 10,
        type: 'yandex#hybrid'
    });

    objectManager = new ymaps.ObjectManager({
        clusterize: true,
        gridSize: 24,
        clusterIconLayout: 'default#pieChart',
        clusterIconPieChartRadius: 16,
        clusterIconPieChartCoreRadius: 8,
        clusterIconPieChartStrokeWidth: 5,
        geoObjectOpenBalloonOnClick: true
    });

    myMap.geoObjects.add(objectManager);
}
function updateMap() {
    getData();
}
function getData() {
    var year = document.getElementById('year').value;
    var month = document.getElementById('month').value;
    var fireType = document.getElementById('fireType').value;

    console.log(year + month + fireType);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'getPoints.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status === 200) {
            var data = JSON.parse(xhr.responseText);
            console.log(data);
            updateMapWithData(data);
        } else {
            console.error('Ошибка при выполнении запроса: ' + xhr.statusText);
        }
    };

    xhr.send('year=' + year + '&month=' + month + '&fireType=' + fireType);
}
function updateMapWithData(data) {
    if (data && data.length > 0) {
        // objectManager.remove(objectManager.objects.getAll('geometry.type', 'Point'));
        myMap.geoObjects.remove(objectManager);
        objectManager.removeAll();

        document.getElementById('totalFires').innerText = 'Количество: ' + data.length;
        var features = data.map(function (location, index) {
            return {
                type: 'Feature',
                id: index,
                geometry: {
                    type: 'Point',
                    coordinates: [location.lat, location.lon]
                },
                properties: {
                    iconContent: '1',
                    label: location.label,
                    hintContent: 'Дата: ' + location.dt
                }
            };
        });

        objectManager.add({
            type: 'FeatureCollection',
            features: features
        });
        myMap.geoObjects.add(objectManager);
    } else {

        document.getElementById('totalFires').innerText = 'Количество: ' + 0;
        console.error('Данные не получены из базы данных.');
    }
}