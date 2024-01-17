
$(document).ready(function () {
    $.ajax({
        url: 'getFireByMonths.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            fillTableData(data);
        },
        error: function (error) {
            console.error('Error', error);
        }
    });

    // Function to populate the table with data
    function fillTableData(data) {
        // Reference to the table body
        var tableBody = $('#firesTableBody');

        // Loop through the data and append rows to the table
        data.forEach(function (entry) {
            var row = '<tr>';
            row += '<td style="font-weight: bold;">' + entry.year + '</td>';
            row += '<td>' + entry.month + '</td>';
            row += '<td>' + entry.count_fires + '</td>';
            row += '</tr>';
            tableBody.append(row);
        });
    }
});
