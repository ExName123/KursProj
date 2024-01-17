var buttons = document.querySelectorAll('.btn-primary');

// Добавляем обработчик события для каждой кнопки
buttons.forEach(function (button) {
    button.addEventListener('click', function () {
        // Убираем класс active у всех кнопок
        buttons.forEach(function (btn) {
            btn.classList.remove('active');
        });

        // Добавляем класс active только для текущей кнопки
        button.classList.add('active');
    });
});
