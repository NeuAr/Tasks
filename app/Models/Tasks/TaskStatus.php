<?php

namespace App\Models\Tasks;

use App\Models\Model;

/**
 * Модель статуса задачи
 *
 * @package App\Models\Tasks
 */
class TaskStatus extends Model
{
    /**
     * Показатель, является ли первичный ключ автоинкрементным
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Показатель, есть ли у модели статуса задачи даты создания и последнего изменения
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Получает правила валидации модели статуса задачи
     *
     * @return array
     */
    protected function getValidationRules() : array
    {
        // Возвращаем список правил валидации модели статуса задачи
        return
            [
                'id' => [ 'required', 'integer', 'min:1', 'max:255',
                    sprintf('unique:%s,%s', TaskStatus::class, 'id') ],
                'name' => [ 'required', 'string', 'min:3', 'max:30' ],
                'color' => [ 'required', 'string', 'min:2', 'max:30', 'regex:/^[a-z]+$/i' ]
            ];
    }

    /**
     * Получает пользовательские сообщения об ошибках валидации модели статуса задачи
     *
     * @return array
     */
    protected function getCustomValidationErrorMessages() : array
    {
        // Возвращаем список пользовательских сообщений об ошибках валидации модели статуса задачи
        return
            [
                'color.regex' => 'Цвет должен состоять только из латинских букв'
            ];
    }

    /**
     * Получает имена атрибутов модели статуса задачи
     *
     * @return array
     */
    protected function getAttributesNames() : array
    {
        // Возвращаем список имён атрибутов модели статуса задачи
        return
            [
                'id' => 'ID',
                'name' => 'Имя',
                'color' => 'Цвет'
            ];
    }
}
