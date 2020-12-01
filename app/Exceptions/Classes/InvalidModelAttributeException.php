<?php


namespace App\Exceptions\Classes;

use Exception;

/**
 * Исключение, которое выбрасывается, когда атрибут модели находится в невалидном состоянии
 *
 * @package App\Exceptions\Classes
 */
class InvalidModelAttributeException extends InvalidModelException
{
    /**
     * Имя атрибута
     *
     * @var string
     */
    private $attributeName = '';

    /**
     * Создаёт экземпляр класса {@see InvalidModelAttributeException}
     *
     * @param string $message Сообщение об ошибке
     * @param string $attributeName Имя атрибута
     * @param string $modelName Имя модели
     * @param int $code Код ошибки
     * @param Exception|null $previous Исключение, послужившее источником текущего исключения
     */
    public function __construct(string $message, string $attributeName = '', string $modelName = '', int $code = 0,
        Exception $previous = null)
    {
        // Сохраняем имя атрибута
        $this->attributeName = $attributeName;

        // Проверяем, было ли передано не пустое имя атрибута
        if (!empty($attributeName))
        {
            // Было передано не пустое имя атрибута
            // Дополняем переданное сообщение об ошибке
            // Проверяем, было ли передано не пустое сообщение об ошибке
            if (!empty($message))
            {
                // Было передано не пустое сообщение об ошибке
                // Дополняем переданное сообщение об ошибке данными об имени атрибута
                $message .= '. Имя атрибута: '.$attributeName;
            }
            else
            {
                // Было передано пустое сообщение об ошибке
                // Сохраняем сообщение об ошибке с данными об имени атрибута
                $message = 'Атрибут ('.$attributeName.') модели находится в невалидном состоянии';
            }
        }

        // Проверяем, было ли передано пустое сообщение об ошибке
        if (empty($message))
        {
            // Было передано пустое сообщение об ошибке
            // Сохраняем сообщение об ошибке для текущего исключения по умолчанию
            $message = 'Атрибут модели находится в невалидном состоянии';
        }

        // Вызываем конструктор родителя с переданными аргументами
        parent::__construct($message, $modelName, $code, $previous);
    }

    /**
     * Получает имя атрибута
     *
     * @return string|null
     */
    final public function getAttributeName()
    {
        // Возвращаем имя атрибута
        return $this->attributeName;
    }
}
