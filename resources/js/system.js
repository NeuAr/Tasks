/**
 * Отправляет HTTP-запрос методом GET
 *
 * @param {string} url URL
 * @param {any} successCallback Функция обратного вызова при успешной отправке HTTP-запроса
 * @param {any} callback Функция обратного вызова после отправки HTTP-запроса
 */
function httpGet(url, successCallback, callback = null)
{
    // Отправляем HTTP-запрос по переданному URL
    $.ajax(
        {
            // URL для отправки HTTP-запроса
            url: url,
            // Метод отправки HTTP-запроса
            method: 'get'
        })
        // Выполняется в случае успешного ответа от сервера, после отправки HTTP-запроса
        .done(function(successResponse)
        {
            // Передаём успешный ответ от сервера, после отправки HTTP-запроса,
            // в переданную функцию обратного вызова при успешной отправке HTTP-запроса
            successCallback(successResponse);
        })
        // Выполняется в случае ответа с ошибкой от сервера, после отправки HTTP-запроса
        .fail(function(errorResponse)
        {
            // Показываем ошибку отправки HTTP-запроса
            showHttpError(errorResponse);
        })
        // Выполняется после ответа от сервера, после отправки HTTP-запроса
        .always(function()
        {
            // Проверяем, была ли указана функция обратного вызова после отправки HTTP-запроса
            if (callback != null)
            {
                // Функция обратного вызова после отправки HTTP-запроса была указана
                // Вызываем переданную функцию обратного вызова после отправки HTTP-запроса
                callback();
            }
        });
}

/**
 * Отправляет HTTP-запрос методом POST
 *
 * @param {string} url URL
 * @param {string} data Тело HTTP-запроса
 * @param {any} successCallback Функция обратного вызова при успешной отправке HTTP-запроса
 * @param {any} callback Функция обратного вызова после отправки HTTP-запроса
 */
function httpPost(url, data, successCallback, callback = null)
{
    // Отправляем HTTP-запрос по переданному URL
    $.ajax(
        {
            // URL для отправки HTTP-запроса
            url: url,
            // Метод отправки HTTP-запроса
            method: 'post',
            // Тело HTTP-запроса
            data: data
        })
        // Выполняется в случае успешного ответа от сервера, после отправки HTTP-запроса
        .done(function(successResponse)
        {
            // Передаём успешный ответ от сервера, после отправки HTTP-запроса,
            // в переданную функцию обратного вызова при успешной отправке HTTP-запроса
            successCallback(successResponse);
        })
        // Выполняется в случае ответа с ошибкой от сервера, после отправки HTTP-запроса
        .fail(function(errorResponse)
        {
            // Показываем ошибку отправки HTTP-запроса
            showHttpError(errorResponse);
        })
        // Выполняется после ответа от сервера, после отправки HTTP-запроса
        .always(function()
        {
            // Проверяем, была ли указана функция обратного вызова после отправки HTTP-запроса
            if (callback != null)
            {
                // Функция обратного вызова после отправки HTTP-запроса была указана
                // Вызываем переданную функцию обратного вызова после отправки HTTP-запроса
                callback();
            }
        });
}

/**
 * Показывает ошибку отправки HTTP-запроса
 *
 * @param {any} errorResponse Ответ с ошибкой от сервера, после отправки HTTP-запроса
 */
function showHttpError(errorResponse)
{
    // Сообщение об ошибке при отправке HTTP-запроса
    let errorMessage = 'Произошла ошибка при отправке HTTP-запроса';

    // Проверяем, есть ли ответ JSON в переданном ответе с ошибкой от сервера, после отправки HTTP-запроса
    if (errorResponse.responseJSON !== undefined)
    {
        // Ответ JSON есть в переданном ответе с ошибкой от сервера, после отправки HTTP-запроса
        // Проверяем, есть ли список ошибок в переданном ответе с ошибкой от сервера, после отправки HTTP-запроса
        if (errorResponse.responseJSON.errors !== undefined)
        {
            // Список ошибок есть в переданном ответе с ошибкой от сервера, после отправки HTTP-запроса
            // Создаём список сообщений об ошибках при отправке HTTP-запроса
            let errorMessages = [];

            // Проходимся по списку ошибок переданного ответа с ошибкой от сервера, после отправки HTTP-запроса
            for (let fieldName in errorResponse.responseJSON.errors)
            {
                // Проходимся по списку ошибок поля формы с текущим именем
                for (let errorMessageIndex in errorResponse.responseJSON.errors[fieldName])
                {
                    // Сохраняем сообщение об ошибке при отправке HTTP-запроса в список
                    errorMessages.push(errorResponse.responseJSON.errors[fieldName][errorMessageIndex]);
                }
            }

            // Показываем ошибки отправки HTTP-запроса
            showError(errorMessages);
            // Прерываем операцию
            return;
        }
        // Проверяем, было ли получено сообщение об ошибке в переданном ответе с ошибкой от сервера,
        // после отправки HTTP-запроса
        else if (errorResponse.responseJSON.message !== undefined)
        {
            // Сообщение об ошибке в переданном ответе с ошибкой от сервера, после отправки HTTP-запроса, было получено
            // Проверяем, не является ли сообщение об ошибке в переданном ответе с ошибкой от сервера,
            // после отправки HTTP-запроса, пустым
            if (errorResponse.responseJSON.message && errorResponse.responseJSON.message.length > 0)
            {
                // Сообщение об ошибке в переданном ответе с ошибкой от сервера, после отправки HTTP-запроса,
                // не является пустым
                // Сохраняем сообщение об ошибке при отправке HTTP-запроса
                errorMessage = 'Ошибка: ' + errorResponse.responseJSON.message;
            }
        }
    }

    // Показываем ошибку отправки HTTP-запроса
    showError(errorMessage);
}

/**
 * Показывает ошибку
 *
 * @param {any} errorMessages Сообщение об ошибке или список сообщений об ошибках
 */
function showError(errorMessages)
{
    // Время, которое нужно показывать ошибку, в секундах
    let errorShowTime = 5;

    // Удаляем все контейнеры ошибок
    $('.fixed-error-box').remove();

    // Получаем уникальный идентификатор контейнера с ошибкой
    let errorBoxId = 'fixed-error-box-id-' + Math.floor(Math.random() * 100000000);
    // Создаём контейнер с ошибкой
    let errorBox = $('<div class="fixed-error-box" id="' + errorBoxId + '"/>');

    // Проверяем, был ли передан список сообщений об ошибках
    if (Array.isArray(errorMessages))
    {
        // Был передан список сообщений об ошибках
        // Создаём контейнер списка сообщений об ошибках
        let errorsBox = $('<div/>');

        // Проходимся по переданному списку сообщений об ошибках
        for (let index = 0; index < errorMessages.length; ++index)
        {
            // Проверяем, является ли текущая ошибка первой в списке
            if (index > 0)
            {
                // Текущая ошибка не является первой в списке
                // Добавляем перенос строки в контейнер списка сообщений об ошибках
                errorsBox.append($('<br/>'));
            }

            // Добавляем в контейнер списка сообщений об ошибках текущее сообщение об ошибке
            errorsBox.append($('<span/>').text(errorMessages[index]));
        }

        // Добавляем в контейнер с ошибкой контейнер списка сообщений об ошибках
        errorBox.append(errorsBox);
    }
    else
    {
        // Было передано сообщение об ошибке
        // Добавляем в контейнер с ошибкой переданное сообщение об ошибке
        errorBox.append($('<div/>').text(errorMessages));
    }

    // Добавляем контейнер с ошибкой в начало тела HTML-документа
    $('body').prepend(errorBox);

    // Через указанное время, которое нужно показывать ошибку, в секундах, удаляем контейнер с ошибкой
    setTimeout(() => $('#' + errorBoxId).remove(), (errorShowTime * 1000));
}
