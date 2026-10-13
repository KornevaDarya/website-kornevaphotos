function checkWebpSupport() {
    // Создаем новое пустое изображение
    const testImage = new Image;

    // Вешаем обработчик на загрузку и ошибку загрузки изображения
    testImage.onload = testImage.onerror = function () {
        // Если высота тестовой картинки равна 1, то она успешно прогрузилась,
        // т.е. формат webp поддерживается. В зависимости от результата теста
        // дабавляем на тег body соответствующий класс.
        document.body.classList.add(testImage.height === 1 ? "page_webp" : "page_no-webp");
    };

    // Начинаем загрузку тестовой webp-картинки в тестовое
    // изображение (прозрачная картинка размером 1 на 1 пиксель)
    testImage.src = "data:image/webp;base64,UklGRhoAAABXRUJQVlA4TA0AAAAvAAAAEAcQERGIiP4HAA==";
}

function getCookie(name) {
    let
        value = '; ' + document.cookie,
        parts = value.split('; ' + name + '=');
    if (parts.length === 2) return parts.pop().split(';').shift();
}