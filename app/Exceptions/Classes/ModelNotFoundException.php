<?php

namespace App\Exceptions\Classes;

use Exception;

/**
 * Исключение, которое выбрасывается, когда модель не найдена
 *
 * @package App\Exceptions\Classes
 */
class ModelNotFoundException extends ModelException
{
    /**
     * ID модели
     *
     * @var mixed
     */
    private $modelId;

    /**
     * Создаёт экземпляр класса {@see ModelNotFoundException}
     *
     * @param string $message Сообщение об ошибке
     * @param mixed $modelId ID модели
     * @param string $modelName Имя модели
     * @param int $code Код ошибки
     * @param Exception|null $previous Исключение, послужившее источником текущего исключения
     */
    public function __construct(string $message, $modelId = null, string $modelName = '', int $code = 0,
        Exception $previous = null)
    {
        // Сохраняем ID модели
        $this->modelId = $modelId;

        // Проверяем, является ли значение переданного ID модели, значением null
        if (!is_null($modelId))
        {
            // Значение переданного ID модели не является значением null
            // Дополняем переданное сообщение об ошибке
            // Проверяем, было ли передано не пустое сообщение об ошибке
            if (!empty($message))
            {
                // Было передано не пустое сообщение об ошибке
                // Дополняем переданное сообщение об ошибке данными об ID модели
                $message .= '. ID модели: '.$modelId;
            }
            else
            {
                // Было передано пустое сообщение об ошибке
                // Сохраняем сообщение об ошибке с данными об ID модели
                $message = 'Модель с ID ('.$modelId.') не найдена';
            }
        }

        // Проверяем, было ли передано пустое сообщение об ошибке
        if (empty($message))
        {
            // Было передано пустое сообщение об ошибке
            // Сохраняем сообщение об ошибке для текущего исключения по умолчанию
            $message = 'Модель не найдена';
        }

        // Вызываем конструктор родителя с переданными аргументами
        parent::__construct($message, $modelName, $code, $previous);
    }

    /**
     * Получает ID модели
     *
     * @return mixed
     */
    final public function getModelId()
    {
        // Возвращаем ID модели
        return $this->modelId;
    }
}
