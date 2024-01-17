document.getElementById('toggleMapPlaces').addEventListener('click', function () {
    window.location.href = 'index.php'; 
});

document.getElementById('toggleDensityMap').addEventListener('click', function () {
    window.location.href = 'mapColors.php';
});

document.getElementById('toggleRegionStats').addEventListener('click', function () {
    window.location.href = 'statistics.php';
});
document.getElementById('toggleDescriptionButton').addEventListener('focus', function() {
    toggleDescriptionButton.style.backgroundColor = '#007bff';
});
function showDescription(){
    var descriptionBlock = $('#descriptionBlock');
    if (descriptionBlock.css('display') === 'none' || descriptionBlock.css('display') === '') {
        descriptionBlock.show();
        document.getElementById('toggleDescriptionButton').style.backgroundColor = '#0b5ed7';
    } else {
        descriptionBlock.hide();
        document.getElementById('toggleDescriptionButton').style.backgroundColor = '#0d6efd';
        document.getElementById('toggleDescriptionButton').style.borderColor = 'transparent';
    }
  }
