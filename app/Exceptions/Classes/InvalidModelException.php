<?php


namespace App\Exceptions\Classes;

use Exception;

/**
 * Исключение, которое выбрасывается, когда модель находится в невалидном состоянии
 *
 * @package App\Exceptions\Classes
 */
class InvalidModelException extends ModelException
{
    /**
     * Создаёт экземпляр класса {@see InvalidModelException}
     *
     * @param string $message Сообщение об ошибке
     * @param string $modelName Имя модели
     * @param int $code Код ошибки
     * @param Exception|null $previous Исключение, послужившее источником текущего исключения
     */
    public function __construct(string $message, string $modelName = '', int $code = 0, Exception $previous = null)
    {
        // Проверяем, было ли передано пустое сообщение об ошибке
        if (empty($message))
        {
            // Было передано пустое сообщение об ошибке
            // Сохраняем сообщение об ошибке для текущего исключения по умолчанию
            $message = 'Модель находится в невалидном состоянии';
        }

        // Вызываем конструктор родителя с переданными аргументами
        parent::__construct($message, $modelName, $code, $previous);
    }
}
