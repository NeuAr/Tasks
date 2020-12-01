<?php

namespace App\Models\Tasks;

use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Модель задачи
 *
 * @package App\Models\Tasks
 */
class Task extends Model
{
    /**
     * Список атрибутов, которые разрешено заполнять из массивов
     *
     * @var array
     */
    protected $fillable = [ 'status_id', 'text' ];

    /**
     * Список атрибутов, типы которых нужно привести к указанным типам
     *
     * @var array
     */
    protected $casts =
        [
            'is_completed' => 'boolean'
        ];

    /**
     * Список значений атрибутов по умолчанию
     *
     * @var array
     */
    protected $attributes = [ 'is_completed' => false ];

    /**
     * Получает правила валидации модели задачи
     *
     * @return array
     */
    protected function getValidationRules() : array
    {
        // Возвращаем список правил валидации модели задачи
        return
            [
                'created_at' => [ 'required', 'date', 'before_or_equal:now', 'before_or_equal:updated_at' ],
                'updated_at' => [ 'required', 'date', 'before_or_equal:now', 'after_or_equal:created_at' ],
                'status_id' => [ 'required', 'integer',
                    sprintf('exists:%s,%s', TaskStatus::class, TaskStatus::getPrimaryKeyName()) ],
                'text' => [ 'required', 'string', 'min:5', 'max:4000' ],
                'is_completed' => [ 'required', 'boolean' ]
            ];
    }

    /**
     * Получает имена атрибутов модели задачи
     *
     * @return array
     */
    protected function getAttributesNames() : array
    {
        // Возвращаем список имён атрибутов модели задачи
        return
            [
                'id' => 'ID',
                'created_at' => 'Дата создания',
                'updated_at' => 'Дата последнего изменения',
                'status_id' => 'ID статуса',
                'text' => 'Текст',
                'is_completed' => 'Показатель, была ли выполнена задача'
            ];
    }

    /**
     * Получает связь со статусом модели задачи
     *
     * @return BelongsTo
     */
    public function relatedStatus() : BelongsTo
    {
        // Возвращаем связь один к одному с моделью статуса задачи
        return $this->belongsTo(TaskStatus::class, 'status_id');
    }
}
