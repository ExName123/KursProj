<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Система мониторинга</title>
    <meta charset="utf-8">
    <script defer src="app/eventsButtons.js"></script>
    <link rel="stylesheet" href="../Styles/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script defer src="app/changeActivating.js"></script>
    <script defer src="app/fillStatisticTableData.js"></script>
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=c8d072d2-801f-425e-b553-da8361e0ee32"></script>
    <script src="https://cdn.anychart.com/releases/8.10.0/js/anychart-bundle.min.js"></script>
    <script src="https://yastatic.net/s3/mapsapi-jslibs/area/0.0.1/util.calculateArea.min.js" type="text/javascript"></script>
    <script src="https://yastatic.net/s3/mapsapi-jslibs/polylabeler/1.0.2/polylabel.min.js" type="text/javascript"></script>
    <script src="app/info.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script defer src="app/createDiagram.js"></script>
    <script defer src="app/createhistogram.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <style>
        .sortable {
            cursor: pointer;
        }
    </style>
</head>

<body style="background-image: url(images/background.jpg);" class="container-fluid">
    <header class="container rounded-start rounded-end bg-primary shadow p-3 mt-2 mb-4 rounded">
        <?php require('html/header.html') ?>
        <button id="toggleMapPlaces" class="btn btn-primary mb-0">Карта мест</button>
        <button id="toggleDensityMap" class="btn btn-primary ms-3 mb-0">Карта распределения</button>
        <button style="background-color: #0b5ed7" id="toggleRegionStats" class="btn btn-primary ms-3 mb-0">Статистика</button>
        <button id="toggleDescriptionButton" onclick="showDescription()" class="btn btn-primary ms-3">Описание</button>

    </header>
    <main class="container mb-5">
        <div class="bg-light shadow p-1 mx-auto mb-5 bg-white rounded" id="contentBlock" style="font-size: 18px;">
            Здесь представлена статистика по пожарам с использованием диаграмм и таблицы.
        </div>
        <div class="bg-light shadow p-1 mx-auto w-50 mb-2 bg-white rounded" id="contentBlock">
            Диаграмма показывает количество пожаров за каждый год
        </div>
        <div id="chartContainer" class="border shadow rounded" style="width: 100%; height: 400px;"></div>

        <div class="bg-light shadow w-50 p-1 mt-4 mx-auto mb-2 bg-white rounded" id="contentBlock">
            Диаграмма показывает количество пожаров за месяц и год
        </div>
        <div id="chartContainerHistogram" class="border shadow rounded" style="width: 100%; height: 400px;"></div>

        <div class="bg-light shadow w-50 p-1 mt-4 mx-auto mb-2 bg-white rounded" id="contentBlock">
            Таблица с количеством пожаров за год и месяц
        </div>

        <div class="d-flex justify-content-center">
            <input class="form-control w-50 mx-auto shadow rounded" id="myInput" type="text" placeholder="Поиск по году или месяцу..">
        </div>

        <div class="container mt-2">
            <table class="table table-bordered table-hover shadow rounded w-50 mx-auto" id="firesTable">
                <thead>
                    <tr>
                        <th class="bg-primary text-white bold sortable" onclick="sortTable(0)">Год</th>
                        <th class="bg-primary text-white bold sortable" onclick="sortTable(1)">Месяц</th>
                        <th class="bg-primary text-white bold sortable" onclick="sortTable(2)">Количество пожаров</th>
                    </tr>
                </thead>
                <tbody id="firesTableBody">
                </tbody>
            </table>
        </div>

    </main>
    <?php require('toggleDescriptionMaps.html');
    require('html/footer.html');  ?>
</body>
<script>
 var sortingOrder = 1; // 1 для сортировки по возрастанию, -1 для сортировки по убыванию

function sortTable(columnIndex) {
    var table, rows, switching, i, x, y, shouldSwitch;
    table = document.getElementById("firesTable");
    switching = true;

    while (switching) {
        switching = false;
        rows = table.rows;

        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("TD")[columnIndex];
            y = rows[i + 1].getElementsByTagName("TD")[columnIndex];

            var xValue = getValue(x.innerHTML);
            var yValue = getValue(y.innerHTML);

            if (sortingOrder * xValue > sortingOrder * yValue) {
                shouldSwitch = true;
                break;
            }
        }

        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
        }
    }
    // Изменение направления сортировки для следующего клика
    sortingOrder *= -1;
}

function getValue(value) {
    if (value.includes('Январь')) return 1;
    else if (value.includes('Февраль')) return 2;
    else if (value.includes('Март')) return 3;
    else if (value.includes('Апрель')) return 4;
    else if (value.includes('Май')) return 5;
    else if (value.includes('Июнь')) return 6;
    else if (value.includes('Июль')) return 7;
    else if (value.includes('Август')) return 8;
    else if (value.includes('Сентябрь')) return 9;
    else if (value.includes('Октябрь')) return 10;
    else if (value.includes('Ноябрь')) return 11;
    else if (value.includes('Декабрь')) return 12;
    else return isNaN(value) ? value.toLowerCase() : parseInt(value);
}
    window.onload = function() {
        showDescription();
    };
    $(document).ready(function() {
        $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#firesTableBody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>

</html>