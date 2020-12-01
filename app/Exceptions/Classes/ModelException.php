<?php

namespace App\Exceptions\Classes;

use Exception;

/**
 * Исключение, которое выбрасывается, когда при выполнении операции с моделью произошла ошибка
 *
 * @package App\Exceptions\Classes
 */
class ModelException extends Exception
{
    /**
     * Имя модели
     *
     * @var string
     */
    private $modelName;

    /**
     * Создаёт экземпляр класса {@see ModelException}
     *
     * @param string $message Сообщение об ошибке
     * @param string $modelName Имя модели
     * @param int $code Код ошибки
     * @param Exception|null $previous Исключение, послужившее источником текущего исключения
     */
    public function __construct(string $message, string $modelName = '', int $code = 0, Exception $previous = null)
    {
        // Сохраняем имя модели
        $this->modelName = $modelName;

        // Проверяем, было ли передано не пустое имя модели
        if (!empty($modelName))
        {
            // Было передано не пустое имя модели
            // Дополняем переданное сообщение об ошибке
            // Проверяем, было ли передано не пустое сообщение об ошибке
            if (!empty($message))
            {
                // Было передано не пустое сообщение об ошибке
                // Дополняем переданное сообщение об ошибке данными об имени модели
                $message .= '. Имя модели: '.$modelName;
            }
            else
            {
                // Было передано пустое сообщение об ошибке
                // Сохраняем сообщение об ошибке с данными об имени модели
                $message = 'При выполнении операции с моделью ('.$modelName.') произошла ошибка';
            }
        }

        // Проверяем, было ли передано пустое сообщение об ошибке
        if (empty($message))
        {
            // Было передано пустое сообщение об ошибке
            // Сохраняем сообщение об ошибке для текущего исключения по умолчанию
            $message = 'При выполнении операции с моделью произошла ошибка';
        }

        // Вызываем конструктор родителя с переданными аргументами
        parent::__construct($message, $code, $previous);
    }

    /**
     * Получает имя модели
     *
     * @return string|null
     */
    final public function getModelName()
    {
        // Возвращаем имя модели
        return $this->modelName;
    }
}
