// Обработчик для кнопки "Карта мест"
document.getElementById('toggleMapPlaces').addEventListener('click', function () {
    // Добавьте ваш код для обработки нажатия на "Карта мест"
   
    window.location.href = 'index.php';
    
});

// Обработчик для кнопки "Карта плотности"
document.getElementById('toggleDensityMap').addEventListener('click', function () {
    // Добавьте ваш код для обработки нажатия на "Карта плотности"
    window.location.href = 'mapColors.php';
});

// Обработчик для кнопки "Статистика регионов"
document.getElementById('toggleRegionStats').addEventListener('click', function () {
    // Добавьте ваш код для обработки нажатия на "Статистика регионов"
    window.location.href = 'statistics.php';
});
document.getElementById('toggleDescriptionButton').addEventListener('focus', function() {
    // Добавляем стили при фокусе
    toggleDescriptionButton.style.backgroundColor = '#007bff';
    // Добавьте другие стили, которые вы хотите применить при фокусе
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
