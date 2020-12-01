<?php

namespace App\Exceptions\Classes;

use Exception;

/**
 * Исключение, которое выбрасывается при вызове метода, недопустимого для текущего состояния объекта
 *
 * @package App\Exceptions\Classes
 */
class InvalidOperationException extends Exception
{
    /**
     * Создаёт экземпляр класса {@see InvalidOperationException}
     *
     * @param string $message Сообщение об ошибке
     * @param int $code Код ошибки
     * @param Exception|null $previous Исключение, послужившее источником текущего исключения
     */
    public function __construct(string $message, int $code = 0, Exception $previous = null)
    {
        // Проверяем, было ли передано пустое сообщение об ошибке
        if (empty($message))
        {
            // Было передано пустое сообщение об ошибке
            // Сохраняем сообщение об ошибке для текущего исключения по умолчанию
            $message = 'Операция недопустима для текущего состояния объекта';
        }

        // Вызываем конструктор родителя с переданными аргументами
        parent::__construct($message, $code, $previous);
    }
}
