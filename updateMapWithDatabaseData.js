var myMap; // Глобальная переменная для хранения объекта карты
var objectManager; // Глобальная переменная для хранения объекта менеджера объектов

ymaps.ready(init);
function init() {
    // Создаем карту на основе существующего элемента с id="map"
    myMap = new ymaps.Map('map', {
        center: [55.7558, 37.6176],
        zoom: 10,
        type: 'yandex#hybrid'
    });

    // Создаем менеджер объектов
    objectManager = new ymaps.ObjectManager({
        clusterize: true,
        gridSize: 32,
        clusterIconLayout: 'default#pieChart',
        clusterIconPieChartRadius: 25,
        clusterIconPieChartCoreRadius: 15,
        clusterIconPieChartStrokeWidth: 3,
        geoObjectOpenBalloonOnClick: true
    });

    // Добавляем менеджер объектов на карту
    myMap.geoObjects.add(objectManager);
}
function updateMap(){
    getData();
}
function getData() {
    // Получаем значения из combobox
    var year = document.getElementById('year').value;
    var month = document.getElementById('month').value;
    var fireType = document.getElementById('fireType').value;

    console.log(year + month + fireType);
    // Выполняем асинхронный запрос к серверу
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'getPoints.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status === 200) {
            // Обработка полученных данных
            var data = JSON.parse(xhr.responseText);
            console.log(data);
            updateMapWithData(data);
        } else {
            console.error('Ошибка при выполнении запроса: ' + xhr.statusText);
        }
    };

    // Отправляем параметры запроса
    xhr.send('year=' + year + '&month=' + month + '&fireType=' + fireType);
}
function updateMapWithData(data) {
    if (data && data.length > 0) {
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
                    label: location.label
                }
            };
        });

        objectManager.add({
            type: 'FeatureCollection',
            features: features
        });

        myMap.geoObjects.add(objectManager);
    } else {
        console.error('Данные не получены из базы данных.');
    }
}