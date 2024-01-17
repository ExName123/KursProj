<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Система мониторинга</title>
    <meta charset="utf-8">
    <script defer src="app/eventsButtons.js"></script>
    <link rel="stylesheet" href="../Styles/style.css">
    <script defer src="app/changeActivating.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=c8d072d2-801f-425e-b553-da8361e0ee32"></script>
    <script src="https://yastatic.net/s3/mapsapi-jslibs/area/0.0.1/util.calculateArea.min.js" type="text/javascript"></script>
    <script src="https://yastatic.net/s3/mapsapi-jslibs/polylabeler/1.0.2/polylabel.min.js" type="text/javascript"></script>
    <script src="app/info.js"></script>
    <script defer src="app/polylabeler.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body style="background-image: url(images/background.jpg);" class="container-fluid">
    <header class="container rounded-start rounded-end bg-primary shadow p-3 mt-4 mb-4 rounded">
        <?php require('html/header.html') ?>
        <button id="toggleMapPlaces" class="btn btn-primary mb-0">Карта мест</button>
        <button style="background-color: #0b5ed7" id="toggleDensityMap" class="btn btn-primary ms-3 mb-0">Карта плотности</button>
        <button id="toggleRegionStats" class="btn btn-primary ms-3 mb-0">Статистика</button>
        <button id="toggleDescriptionButton" onclick="showDescription()" class="btn btn-primary ms-3">Описание</button>

    </header>
    <main class="container mb-5">
        <?php require('html/buttonsMain.html') ?>

        <div id="map" class="inverted-map mt-3"></div>
        <div class="overlay-filter"></div>
    </main>
    <?php require('html/footer.html') ?>
</body>

</html>