<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Система мониторинга</title>
    <meta charset="utf-8">
    <script defer src="eventsButtons.js"></script>
    <link rel="stylesheet" href="../Styles/style.css">
    <script defer src="changeActivating.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=c8d072d2-801f-425e-b553-da8361e0ee32"></script>
    <script src="https://yastatic.net/s3/mapsapi-jslibs/area/0.0.1/util.calculateArea.min.js" type="text/javascript"></script>
    <script src="https://yastatic.net/s3/mapsapi-jslibs/polylabeler/1.0.2/polylabel.min.js" type="text/javascript"></script>
    <script src="info.js"></script>
    <script defer src="polylabeler.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body style="background-image: url(background.jpg);" class="container-fluid">
    <header class="container rounded-start rounded-end bg-primary shadow p-3 mt-4 mb-4 rounded">
        <div class="d-flex align-items-center">
            <img style="width: 3em; height: 3em;" class="rounded-pill" src="fire-icon.avif">
            <h2 class="text-white ms-3 mb-0">Сервис по мониторингу пожаров</h2>
            <div class="d-flex text-white ms-auto">
                <h6 class="mb-0"><a class="nav-link link-light" href="login.html">Авторизация</a></h6>
                <h6 class="ms-3 mb-0"><a class="nav-link link-light" href="register.php">Регистрация</a></h6>
            </div>
        </div>
        <hr class="my-2">
        <div class="d-flex text-white ms-auto mt-3">
            <div id="descriptionBlock" style="display: none;">
                <h5 class="text-white text-center mt-3">Сервис позволяет визуализировать на карте места возгорания, для этого необходимо использовать соответствующие фильтры,
                    визуализация происходит на 2 картах, первая из них позволяет отследить конкретное место в стране,
                    вторая карта позволить просмотреть плотность возниковения очагов возгарания, также пользователю представлена статистика
                    по регионам в виде таблицы с кластеризацией данных и отображением самых подверженных возгараниям регионов.
                </h5>
                <hr class="my-2">
            </div>
        </div>
        <button id="toggleMapPlaces" class="btn btn-primary mb-0">Карта мест</button>
        <button style="background-color: #0b5ed7" id="toggleDensityMap" class="btn btn-primary ms-3 mb-0">Карта плотности</button>
        <button id="toggleRegionStats" class="btn btn-primary ms-3 mb-0">Статистика регионов</button>
        <button id="toggleDescriptionButton" onclick="showDescription()" class="btn btn-primary ms-3">Описание</button>

    </header>
    <main class="container mb-5">
        <div class="d-flex">
            <div class="col-md-3">
                <label for="year" class="form-label">Выберите год:</label>
                <select class="form-select" id="year">
                    <option value="2021">2021</option>
                    <option value="2018">2018</option>
                    <option value="2012">2012</option>
                </select>
            </div>

            <div class="col-md-3 ms-3">
                <label for="month" class="form-label">Выберите месяц:</label>
                <select class="form-select" id="month">
                    <option value="1">Январь</option>
                    <option value="2">Февраль</option>
                    <option value="3">Март</option>
                    <option value="4">Апрель</option>
                    <option value="5">Май</option>
                    <option value="6">Июнь</option>
                    <option value="7">Июль</option>
                    <option value="8">Август</option>
                    <option value="9">Сентябрь</option>
                    <option value="10">Октябрь</option>
                    <option value="11">Ноябрь</option>
                    <option value="12">Декабрь</option>
                </select>
            </div>

            <div class="col-md-3 ms-3">
                <label for="fireType" class="form-label">Выберите тип пожара:</label>
                <select class="form-select" id="fireType">
                    <option value="1">Неконтролируемый пал</option>
                    <option value="2">Торфяной пожар</option>
                    <option value="3">Лесной пожар</option>
                    <option value="4">Природный пожар</option>
                    <option value="5">Контролируемый пал</option>
                </select>
            </div>

            <div class="align-self-end ms-3">
                <button id="updateButton" class="btn btn-primary" onclick="updateMap()">Обновить карту</button>
            </div>
            <div class="align-self-end ms-2" id="totalFires">Количество: 0</div>
        </div>

        <div id="map" class="inverted-map mt-3"></div>
        <div class="overlay-filter"></div>
    </main>
    <footer class="mt-auto text-bg-primary rounded-start rounded-end">
        <div class="d-flex justify-content-center">
            <form class="border-end p-3">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                    <div id="emailHelp" class="form-text">Ваш email не будет передан 3-м лицам</div>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1">
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">Check me out</label>
                </div>
                <button type="submit" class="btn btn-primary float-end">Submit</button>
            </form>
            <div class="my-auto ms-3">
                <div class="d-inline mb-3">
                    <div class="d-flex">
                        <img class="rounded-pill" style="width: 35px; height: 30px;" src="images/logo.jpg">
                        <h6 class="ms-3">Телефон: +7(943)-293 21 32</h6>
                    </div>
                    <div class="d-flex mt-3">
                        <img class="rounded-pill" style="width: 35px; height: 30px;" src="images/skype.jpg">
                        <h6 class="ms-3">Skype: +housesSale_92</h6>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>