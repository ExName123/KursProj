anychart.onDocumentReady(function() {
    // Вызываем PHP-скрипт для получения данных
    fetch('getStatistics.php')
        .then(response => response.json())
        .then(data => {

            // Преобразуем полученные данные в формат, подходящий для AnyChart
            var chartData = data.map(function(row) {
                return { x: row.year, value: row.fire_count };
            });

            // Создаем график и добавляем данные на него
            var chart = anychart.bar();

            // Добавляем ось X с названиями годов
            var xAxis = chart.xAxis();
            xAxis.title('Года');
            xAxis.labels().format('{%Value}');

            // Добавляем ось Y с названием "Количество пожаров"
            var yAxis = chart.yAxis();
            yAxis.title('Количество пожаров');

            // Добавляем подсказку с отображением конкретного количества
            chart.tooltip().format('Количество пожаров: {%value}');

            chart.data(chartData);
            chart.container('chartContainer');
            chart.draw();
        })
        .catch(error => console.error('Error:', error));
});
