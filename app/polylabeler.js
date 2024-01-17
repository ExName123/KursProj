var map; 
var objectManager; 
let totalPoints;
ymaps.ready(init);
function init() {
    ymaps.ready(['polylabel.create']).then(function () {
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
            updateLabels('text');
            typeList.collapse();
        });
        typeList.get(1).events.add('click', function () {
            typeList.get(0).state.set('selected', false);
            updateLabels('image');
            typeList.collapse();
        });
        map.controls
            .add(typeList, { floatIndex: 0 })
            .add(zoomControl);

        objectManager = new ymaps.ObjectManager();
        ymaps.borders.load('RU', {
            lang: 'ru',
            quality: 2
        }).then(function (result) {
            var options = {
                labelDefaults: 'dark',
                labelLayout: textLayouts.label,
                fillColor: 'rgba(247, 247, 247, 0.7)',
                strokeColor: 'rgba(194, 194, 194, 1)',
                labelTextSize: { '3_6': 12, '7_18': 14 },
                cursor: 'grab',
                labelDotCursor: 'pointer',
                labelPermissibleInaccuracyOfVisibility: 4
            };
     
            objectManager.add(result.features.map(function (feature) {
                feature.id = feature.properties.iso3166;
   
                feature.properties.regionName = feature.properties.iso3166;

                feature.options = options;
                return feature;
            }));

            map.geoObjects.add(objectManager);

    
            var polylabel = new ymaps.polylabel.create(map, objectManager);


            objectManager.events.add(['labelmouseenter', 'labelmouseleave'], function (event) {
          
                var polygon = objectManager.objects.getById(event.get('objectId'));

                var state = polylabel.getLabelState(polygon);
     
                var centerProj = map.options.get('projection').toGlobalPixels(state.get('center'), map.getZoom());
                if (event.get('type') === 'labelmouseenter' && state.get('currentVisibility') === 'dot') {
                    objectManager.objects.hint.open(polygon.id, centerProj);
                } else {
                    objectManager.objects.hint.close();
                }
            });
        });


    });
    function updateLabels(type) {
        var layouts = type === 'text' ? textLayouts : imgLayouts;
     
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

    var year = document.getElementById('year').value;
    var month = document.getElementById('month').value;
    var fireType = document.getElementById('fireType').value;

    console.log(year + month + fireType);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'getPoints.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status === 200) {
       
            console.log('Raw Response:', xhr.responseText);

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


    xhr.send('year=' + year + '&month=' + month + '&fireType=' + fireType);
} function updateMapWithData(data) {
    if (data && data.length > 0) {
        document.getElementById('totalFires').innerText = 'Количество: ' + data.length;

        var polygons = [];
        var polygonsData = new Map();

        objectManager.objects.each(function (feature) {
            if (feature.geometry.type === "Polygon") {
                polygons.push(feature);
         
                polygonsData.set(feature.id, { color: '', counter: 0 });
            }
        });

        data.forEach(function (point) {
   
            var pointCoords = [point.lat, point.lon];
  
            var insidePolygon = polygons.find(function (polygon) {
                var polygonBounds = ymaps.util.bounds.fromPoints(polygon.geometry.coordinates[0]);
                return ymaps.util.bounds.containsPoint(polygonBounds, pointCoords);
            });

            if (insidePolygon) {
                var polygonId = insidePolygon.id;
                console.log('Точка ID:', point.id, 'находится внутри полигона ID:', polygonId);

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
         
            var alpha = 0.6 + 0.4 * ((percentage - 10) / 10);
            data.color = 'rgba(255, 165, 0, ' + alpha + ')';
        } else if (percentage > 20) {
            data.color = 'rgba(255, 0, 0, 0.7)'; // Красный
        }
    });
}
function repaintMap(polygonsData) {

    if (map) {
        map.destroy();
    }

   setColorPercent(polygonsData);

    ymaps.ready(['polylabel.create']).then(function () {

        var textLayouts = {
            label: '<div>{{properties.hintContent}}</div>',
            hint: ymaps.templateLayoutFactory.createClass('<div>{{properties.hintContent}}</div>')
        };
        var imgLayouts = {
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
        
            updateLabels('text');
      
            typeList.collapse();
        });
        typeList.get(1).events.add('click', function () {
            typeList.get(0).state.set('selected', false);
        
            updateLabels('image');
      
            typeList.collapse();
        });
        map.controls
            .add(typeList, { floatIndex: 0 })
            .add(zoomControl);

    
        objectManager = new ymaps.ObjectManager();
     
        ymaps.borders.load('RU', {
            lang: 'ru',
            quality: 2
        }).then(function (result) {
            var options = {
   
                labelDefaults: 'dark',
            
                labelLayout: textLayouts.label,
      
                fillColor: 'rgba(247, 247, 247, 0.7)',
            
                strokeColor: 'rgba(194, 194, 194, 1)',
        
                labelTextSize: { '3_6': 12, '7_18': 14 },
                cursor: 'grab',
                labelDotCursor: 'pointer',
    
                labelPermissibleInaccuracyOfVisibility: 4
            };
            objectManager.add(result.features.map(function (feature) {
                feature.id = feature.properties.iso3166;

            
                feature.properties.regionName = feature.properties.iso3166;

                var { color, counter } = polygonsData.get(feature.id);

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

            var polylabel = new ymaps.polylabel.create(map, objectManager);

            objectManager.events.add(['labelmouseenter', 'labelmouseleave'], function (event) {

                var polygon = objectManager.objects.getById(event.get('objectId'));
             
                var state = polylabel.getLabelState(polygon);
          
                var centerProj = map.options.get('projection').toGlobalPixels(state.get('center'), map.getZoom());
                if (event.get('type') === 'labelmouseenter' && state.get('currentVisibility') === 'dot') {
                    objectManager.objects.hint.open(polygon.id, centerProj);
                } else {
                    objectManager.objects.hint.close();
                }
            });
        });
    });

    function updateLabels(type) {
        var layouts = type === 'text' ? textLayouts : imgLayouts;

        objectManager.objects.options.set({
            hintContentLayout: layouts.hint
        });
        objectManager.objects.each(function (polygon) {
            objectManager.objects.setObjectOptions(polygon.id, { labelLayout: layouts.label });
        });
    }
}