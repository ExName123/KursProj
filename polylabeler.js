var map; // Глобальная переменная для хранения объекта карты
var objectManager; // Глобальная переменная для хранения объекта менеджера объектов
let totalPoints;
ymaps.ready(init);
function init() {
    ymaps.ready(['polylabel.create']).then(function () {
        // Создадим два макета подписей полигонов: с текстом и с картинкой.
        var textLayouts = {
            label: '<div>{{properties.hintContent}}</div>',
            hint: ymaps.templateLayoutFactory.createClass('<div>{{properties.hintContent}}</div>')
        };
        map = new ymaps.Map('map', {
            center: [58, 40],
            zoom: 5,
            controls: []
        }, {
            maxZoom: 18,
            minZoom: 2
        });

        // Создадим переключатель вида подписей.
        var typeList = new ymaps.control.ListBox({
            data: {
                content: 'Тип подписи'
            },
            items: [
                new ymaps.control.ListBoxItem({ data: { content: 'Текст' }, state: { selected: true } }),
                new ymaps.control.ListBoxItem({ data: { content: 'Изображение' } })
            ]
        }),
            zoomControl = new ymaps.control.ZoomControl({
                options: {
                    size: 'small',
                    float: 'none',
                    position: {
                        bottom: 145,
                        right: 10
                    }
                }
            });
        typeList.get(0).events.add('click', function () {
            typeList.get(1).state.set('selected', false);
            // Устанавливаем всем подписям текстовый макет.
            updateLabels('text');
            // Закрываем список.
            typeList.collapse();
        });
        typeList.get(1).events.add('click', function () {
            typeList.get(0).state.set('selected', false);
            // Устанавливаем всем подписям макет с картинкой.
            updateLabels('image');
            // Закрываем список.
            typeList.collapse();
        });
        map.controls
            .add(typeList, { floatIndex: 0 })
            .add(zoomControl);

        // Создадим менеджер объектов.
        objectManager = new ymaps.ObjectManager();
        // Загрузим регионы.
        ymaps.borders.load('RU', {
            lang: 'ru',
            quality: 2
        }).then(function (result) {
            var options = {
                // Стандартный вид текста будет темный с белой обводкой.
                labelDefaults: 'dark',
                // Макет подписи.
                labelLayout: textLayouts.label,
                // Цвет заливки.
                fillColor: 'rgba(247, 247, 247, 0.7)',
                // Цвет обводки.
                strokeColor: 'rgba(194, 194, 194, 1)',
                // Размер текста подписей зависит от масштаба.
                // На уровнях зума 3-6 размер текста равен 12, а на уровнях зума 7-18 равен 14.
                labelTextSize: { '3_6': 12, '7_18': 14 },
                cursor: 'grab',
                labelDotCursor: 'pointer',
                // Допустимая погрешность в расчете вместимости подписи в полигон.
                labelPermissibleInaccuracyOfVisibility: 4
            };
            // Добавляем полигоны в менеджер объектов.
            objectManager.add(result.features.map(function (feature) {
                feature.id = feature.properties.iso3166;
                // В свойство regionName запишем название региона.
                feature.properties.regionName = feature.properties.iso3166;
                // Присваиваем регионам опции, нужные для модуля подписей полигонов.
                feature.options = options;
                return feature;
            }));

            map.geoObjects.add(objectManager);

            // Запускаем модуль подписей.
            var polylabel = new ymaps.polylabel.create(map, objectManager);

            // Подписываемся на события подписей.
            objectManager.events.add(['labelmouseenter', 'labelmouseleave'], function (event) {
                // Получаем полигон, на котором произошло событие.
                var polygon = objectManager.objects.getById(event.get('objectId'));
                // Получаем состояние подписи.
                var state = polylabel.getLabelState(polygon);
                // Получаем проекцию позиции подписи, чтобы показать на этом месте всплывающую подсказку.
                var centerProj = map.options.get('projection').toGlobalPixels(state.get('center'), map.getZoom());
                if (event.get('type') === 'labelmouseenter' && state.get('currentVisibility') === 'dot') {
                    objectManager.objects.hint.open(polygon.id, centerProj);
                } else {
                    objectManager.objects.hint.close();
                }
            });
        });

        // Функция, которая обновляет у всех полигонов макет.
    });
    function updateLabels(type) {
        var layouts = type === 'text' ? textLayouts : imgLayouts;
        // Меняем всплывающую подсказку в зависимости от макета.
        objectManager.objects.options.set({
            hintContentLayout: layouts.hint
        });
        objectManager.objects.each(function (polygon) {
            objectManager.objects.setObjectOptions(polygon.id, { labelLayout: layouts.label });
        });
    }
}
function updateMap() {
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
            // Output the raw response text to the console
            console.log('Raw Response:', xhr.responseText);

            // Attempt to parse the response as JSON
            try {
                var data = JSON.parse(xhr.responseText);
                console.log('Parsed Data:', data);
                totalPoints = data.length;
                updateMapWithData(data);
            } catch (error) {
                console.error('Error parsing JSON:', error);
            }
        } else {
            console.error('Ошибка при выполнении запроса: ' + xhr.statusText);
        }
    };

    // Отправляем параметры запроса
    xhr.send('year=' + year + '&month=' + month + '&fireType=' + fireType);
} function updateMapWithData(data) {
    if (data && data.length > 0) {
        document.getElementById('totalFires').innerText = 'Количество: ' + data.length;
        // Extract polygons from the object manager
        var polygons = [];
        var polygonsData = new Map();

        objectManager.objects.each(function (feature) {
            if (feature.geometry.type === "Polygon") {
                polygons.push(feature);
                // Initialize counter for each polygon
                polygonsData.set(feature.id, { color: '', counter: 0 });
            }
        });

        // Iterate over each data point
        data.forEach(function (point) {
            // Create a point object
            var pointCoords = [point.lat, point.lon];

            // Check if the point is inside any polygon
            var insidePolygon = polygons.find(function (polygon) {
                var polygonBounds = ymaps.util.bounds.fromPoints(polygon.geometry.coordinates[0]);
                return ymaps.util.bounds.containsPoint(polygonBounds, pointCoords);
            });

            // Output the result to the console
            if (insidePolygon) {
                var polygonId = insidePolygon.id;
                console.log('Точка ID:', point.id, 'находится внутри полигона ID:', polygonId);

                // Increment the counter for the polygon
                var data = polygonsData.get(polygonId);
                var counter = isNaN(data.counter) ? 0 : data.counter;
                polygonsData.set(polygonId, { color: data.color, counter: counter + 1 });
                console.log('Текущий счетчик для полигона ID:', polygonId, 'равен:', polygonsData.get(polygonId));
            } else {
                console.log('Точка ID:', point.id, 'не находится внутри полигонов');
            }
        });

        repaintMap(polygonsData);
    } else {
        console.error('Данные не получены из базы данных.');
    }
}
function setColorPercent(polygonsData) {
    polygonsData.forEach(function (data, polygonId) {
        var percentage = (data.counter / totalPoints) * 100;

        if (percentage === 0) {
            data.color = 'rgba(40, 222, 79, 0.7)'; // Зеленый
        } else if (percentage < 10) {
            data.color = 'rgba(255, 165, 0, 0.4)'; // Слегка оранжевый
        } else if (percentage >= 10 && percentage <= 20) {
            // Раскрась оранжевым с разной прозрачностью от 0.4 до 0.7
            var alpha = 0.6 + 0.4 * ((percentage - 10) / 10);
            data.color = 'rgba(255, 165, 0, ' + alpha + ')';
        } else if (percentage > 20) {
            data.color = 'rgba(255, 0, 0, 0.7)'; // Красный
        }
    });
}
function repaintMap(polygonsData) {
    // Clear the existing map
    if (map) {
        map.destroy();
    }

   setColorPercent(polygonsData);

    // Rest of your existing code for map initialization
    ymaps.ready(['polylabel.create']).then(function () {
        // Создадим два макета подписей полигонов: с текстом и с картинкой.
        var textLayouts = {
            label: '<div>{{properties.hintContent}}</div>',
            hint: ymaps.templateLayoutFactory.createClass('<div>{{properties.hintContent}}</div>')
        };
        var imgLayouts = {
            // В свойстве regionName содержится название региона.
            label: '<img src="images/{{properties.regionName}}.png" height="50px"/>',
            hint: ymaps.templateLayoutFactory.createClass('<img src="images/{{properties.regionName}}.png" height="50px"/>')
        };
        map = new ymaps.Map('map', {
            center: [58, 40],
            zoom: 5,
            controls: []
        }, {
            maxZoom: 18,
            minZoom: 2
        });

        // Создадим переключатель вида подписей.
        var typeList = new ymaps.control.ListBox({
            data: {
                content: 'Тип подписи'
            },
            items: [
                new ymaps.control.ListBoxItem({ data: { content: 'Текст' }, state: { selected: true } }),
                new ymaps.control.ListBoxItem({ data: { content: 'Изображение' } })
            ]
        }),
            zoomControl = new ymaps.control.ZoomControl({
                options: {
                    size: 'small',
                    float: 'none',
                    position: {
                        bottom: 145,
                        right: 10
                    }
                }
            });
        typeList.get(0).events.add('click', function () {
            typeList.get(1).state.set('selected', false);
            // Устанавливаем всем подписям текстовый макет.
            updateLabels('text');
            // Закрываем список.
            typeList.collapse();
        });
        typeList.get(1).events.add('click', function () {
            typeList.get(0).state.set('selected', false);
            // Устанавливаем всем подписям макет с картинкой.
            updateLabels('image');
            // Закрываем список.
            typeList.collapse();
        });
        map.controls
            .add(typeList, { floatIndex: 0 })
            .add(zoomControl);

        // Создадим менеджер объектов.
        objectManager = new ymaps.ObjectManager();
        // Загрузим регионы.
        ymaps.borders.load('RU', {
            lang: 'ru',
            quality: 2
        }).then(function (result) {
            var options = {
                // Стандартный вид текста будет темный с белой обводкой.
                labelDefaults: 'dark',
                // Макет подписи.
                labelLayout: textLayouts.label,
                // Цвет заливки.
                fillColor: 'rgba(247, 247, 247, 0.7)',
                // Цвет обводки.
                strokeColor: 'rgba(194, 194, 194, 1)',
                // Отключим показ всплывающей подсказки при наведении на полигон.

                // Размер текста подписей зависит от масштаба.
                // На уровнях зума 3-6 размер текста равен 12, а на уровнях зума 7-18 равен 14.
                labelTextSize: { '3_6': 12, '7_18': 14 },
                cursor: 'grab',
                labelDotCursor: 'pointer',
                // Допустимая погрешность в расчете вместимости подписи в полигон.
                labelPermissibleInaccuracyOfVisibility: 4
            };
            objectManager.add(result.features.map(function (feature) {
                feature.id = feature.properties.iso3166;

                // В свойство regionName запишем название региона.
                feature.properties.regionName = feature.properties.iso3166;

                // Получаем цвет и счетчик из polygonsData
                var { color, counter } = polygonsData.get(feature.id);

                // Присваиваем регионам опции, нужные для модуля подписей полигонов, включая цвет
                feature.options = {
                    labelLayout: textLayouts.label,
                    fillColor: color,
                    strokeColor: 'rgba(81, 80, 102, 1)',
                    labelTextSize: { '3_6': 12, '7_18': 14 },
                    cursor: 'grab',
                    labelDotCursor: 'pointer',
                    labelPermissibleInaccuracyOfVisibility: 4,
                    hintContentLayout: ymaps.templateLayoutFactory.createClass(
                        '<div>{{ properties.hintContent }}</div><div>Количество пожаров: ' + counter + '</div>'
                    )
                };

                return feature;
            }));
            map.geoObjects.add(objectManager);

            // Запускаем модуль подписей.
            var polylabel = new ymaps.polylabel.create(map, objectManager);

            // Подписываемся на события подписей.
            objectManager.events.add(['labelmouseenter', 'labelmouseleave'], function (event) {
                // Получаем полигон, на котором произошло событие.
                var polygon = objectManager.objects.getById(event.get('objectId'));
                // Получаем состояние подписи.
                var state = polylabel.getLabelState(polygon);
                // Получаем проекцию позиции подписи, чтобы показать на этом месте всплывающую подсказку.
                var centerProj = map.options.get('projection').toGlobalPixels(state.get('center'), map.getZoom());
                if (event.get('type') === 'labelmouseenter' && state.get('currentVisibility') === 'dot') {
                    objectManager.objects.hint.open(polygon.id, centerProj);
                } else {
                    objectManager.objects.hint.close();
                }
            });
        });

        // Функция, которая обновляет у всех полигонов макет.
    });

    function updateLabels(type) {
        var layouts = type === 'text' ? textLayouts : imgLayouts;
        // Меняем всплывающую подсказку в зависимости от макета.
        objectManager.objects.options.set({
            hintContentLayout: layouts.hint
        });
        objectManager.objects.each(function (polygon) {
            objectManager.objects.setObjectOptions(polygon.id, { labelLayout: layouts.label });
        });
    }
}