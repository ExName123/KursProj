anychart.onDocumentReady(function() {
    fetch('getStatistics.php')
        .then(response => response.json())
        .then(data => {

            var chartData = data.map(function(row) {
                return { x: row.year, value: row.fire_count };
            });

            var chart = anychart.bar();

            var xAxis = chart.xAxis();
            xAxis.title('Года');
            xAxis.labels().format('{%Value}');

            var yAxis = chart.yAxis();
            yAxis.title('Количество пожаров');

            chart.tooltip().format('Количество пожаров: {%value}');

            chart.data(chartData);
            chart.container('chartContainer');
            chart.draw();
        })
        .catch(error => console.error('Error:', error));
});
