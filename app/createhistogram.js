fetch('getFireByMonths.php')
.then(response => response.json())
.then(data => {
    console.log(data);
    data.sort(function(a, b) {
        return parseInt(a.month) - parseInt(b.month);
    });

    moment.locale('ru');

    var dataSet = [];
    data.forEach(function(item) {
        var monthName = moment(item.year + '-' + item.month, 'YYYY-M').format('MMM');
        var yearMonth = moment(item.year + '-' + item.month, 'YYYY-M').format('MMM YYYY');
        dataSet.push({x: monthName, value: parseInt(item.count_fires), yearMonth: yearMonth});
    });

    var chart = anychart.column();

    chart.data(dataSet);

    chart.xAxis().title('Месяц');
    chart.yAxis().title('Количество пожаров');

    chart.tooltip().format('Количество пожаров: {%value}');
    chart.tooltip().fontSize(16);  

    chart.container('chartContainerHistogram');  
    chart.draw();
});


