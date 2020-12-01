<?php

namespace App\Models;

use App\Exceptions\Classes\InvalidModelAttributeException;
use App\Exceptions\Classes\InvalidModelException;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Класс модели
 *
 * @package App\Models
 */
abstract class Model extends BaseModel
{
    /**
     * Выполняет действия, необходимые после загрузки модели
     *
     * @return void
     */
    protected static function booted() : void
    {
        // Регистрируем событие, возникающее перед созданием модели
        static::creating(function (Model $model)
        {
            // Выполняем операции перед созданием модели
            $model->beforeCreate();
            // Устанавливаем пустым датам создания и изменения модели значения по умолчанию
            $model->setEmptyTimestampsDefaultValue();
            // Выполняем валидацию модели
            $model->validate();
            // Проверяем первичный ключ перед созданием модели
            $model->checkPrimaryKeyBeforeCreate();
        });

        // Регистрируем событие, возникающее перед обновлением модели
        static::updating(function (Model $model)
        {
            // Выполняем операции перед обновлением модели
            $model->beforeUpdate();
            // Выполняем валидацию только изменённых атрибутов модели
            $model->validateChanges();
            // Проверяем первичный ключ перед обновлением модели
            $model->checkPrimaryKeyBeforeUpdate();
        });

        // Регистрируем событие, возникающее перед удалением модели
        static::deleting(function (Model $model)
        {
            // Выполняем операции перед удалением модели
            $model->beforeDelete();
            // Выполняем валидацию модели перед удалением
            $model->validate(true);
        });
    }

    /**
     * Получает имя атрибута первичного ключа модели
     *
     * @return string
     */
    public static function getPrimaryKeyName() : string
    {
        // Создаём экземпляр класса модели, для которой нужно получить имя атрибута первичного ключа
        $model = new static();
        // Возвращаем имя атрибута первичного ключа модели
        return $model->getKeyName();
    }

    /**
     * Проверяет первичный ключ модели
     *
     * @return void
     * @throws InvalidModelException Автоинкрементный первичный ключ модели является составным
     * @throws InvalidModelException Составной первичный ключ модели не имеет атрибутов
     * @throws InvalidModelException Составной первичный ключ модели имеет атрибут с пустым именем
     * @throws InvalidModelException Имя атрибута первичного ключа модели является пустым
     */
    private function checkPrimaryKey() : void
    {
        // Проверяем, является ли первичный ключ модели составным
        if (is_array($this->primaryKey))
        {
            // Первичный ключ модели является составным
            // Проверяем, является ли первичный ключ модели автоинкрементным
            if ($this->incrementing)
            {
                // Первичный ключ модели является автоинкрементным
                // Выбрасываем исключение
                throw new InvalidModelException('Автоинкрементный первичный ключ модели является составным',
                    get_class($this));
            }

            // Проверяем, имеет ли составной первичный ключ модели атрибуты
            if (count($this->primaryKey) == 0)
            {
                // Составной первичный ключ модели не имеет атрибутов
                // Выбрасываем исключение
                throw new InvalidModelException('Составной первичный ключ модели не имеет атрибутов',
                    get_class($this));
            }

            // Проходимся по атрибутам составного первичного ключа модели
            foreach ($this->primaryKey as $attributeName)
            {
                // Проверяем, является ли имя текущего атрибута модели пустым или равным null
                if (is_null($attributeName) OR $attributeName == '')
                {
                    // Имя текущего атрибута модели является пустым или равным null
                    // Выбрасываем исключение
                    throw new InvalidModelException(
                        'Составной первичный ключ модели имеет атрибут с пустым именем',
                        get_class($this));
                }
            }
        }
        else
        {
            // Первичный ключ модели является простым
            // Проверяем, является ли имя атрибута первичного ключа модели пустым или равным null
            if (is_null($this->primaryKey) OR $this->primaryKey == '')
            {
                // Имя атрибута первичного ключа модели является пустым или равным null
                // Выбрасываем исключение
                throw new InvalidModelException('Имя атрибута первичного ключа модели является пустым',
                    get_class($this));
            }
        }
    }

    /**
     * Проверяет первичный ключ перед созданием модели
     *
     * @return void
     * @throws InvalidModelAttributeException
     * Значение атрибута автоинкрементного первичного ключа модели было установлено
     * @throws InvalidModelException Автоинкрементный первичный ключ модели является составным
     * @throws InvalidModelException Составной первичный ключ модели не имеет атрибутов
     * @throws InvalidModelException Составной первичный ключ модели имеет атрибут с пустым именем
     * @throws InvalidModelException Имя атрибута первичного ключа модели является пустым
     */
    private function checkPrimaryKeyBeforeCreate() : void
    {
        // Проверяем первичный ключ модели
        $this->checkPrimaryKey();

        // Проверяем, является ли первичный ключ модели автоинкрементным
        if ($this->incrementing)
        {
            // Первичный ключ модели является автоинкрементным
            // Проверяем, было ли установлено значение атрибута с именем атрибута первичного ключа модели
            if ($this->isDirty($this->primaryKey))
            {
                // Значение атрибута с именем атрибута первичного ключа модели было установлено
                // Выбрасываем исключение
                throw new InvalidModelAttributeException(
                    'Значение атрибута автоинкрементного первичного ключа модели было установлено',
                    $this->primaryKey, get_class($this));
            }
        }
    }

    /**
     * Проверяет первичный ключ перед обновлением модели
     *
     * @return void
     * @throws InvalidModelAttributeException Значение атрибута первичного ключа модели было изменено
     * @throws InvalidModelException Автоинкрементный первичный ключ модели является составным
     * @throws InvalidModelException Составной первичный ключ модели не имеет атрибутов
     * @throws InvalidModelException Составной первичный ключ модели имеет атрибут с пустым именем
     * @throws InvalidModelException Имя атрибута первичного ключа модели является пустым
     */
    private function checkPrimaryKeyBeforeUpdate() : void
    {
        // Проверяем первичный ключ модели
        $this->checkPrimaryKey();

        // Проверяем, является ли первичный ключ модели составным
        if (is_array($this->primaryKey))
        {
            // Первичный ключ модели является составным
            // Проходимся по атрибутам составного первичного ключа модели
            foreach ($this->primaryKey as $attributeName)
            {
                // Проверяем, было ли изменено значение атрибута модели с текущим именем
                if ($this->isDirty($attributeName))
                {
                    // Значение атрибута модели с текущим именем было изменено
                    // Выбрасываем исключение
                    throw new InvalidModelAttributeException(
                        'Значение атрибута первичного ключа модели было изменено',
                        $attributeName, get_class($this));
                }
            }
        }
        else
        {
            // Первичный ключ модели является простым
            // Проверяем, было ли изменено значение атрибута с именем атрибута первичного ключа модели
            if ($this->isDirty($this->primaryKey))
            {
                // Значение атрибута с именем атрибута первичного ключа модели было изменено
                // Выбрасываем исключение
                throw new InvalidModelAttributeException(
                    'Значение атрибута первичного ключа модели было изменено',
                    $this->primaryKey, get_class($this));
            }
        }
    }

    /**
     * Устанавливает пустым датам создания и изменения модели значения по умолчанию
     *
     * @return void
     */
    private function setEmptyTimestampsDefaultValue() : void
    {
        // Проверяем, есть ли у модели даты создания и изменения
        if (!$this->usesTimestamps())
        {
            // У модели нет дат создания и изменения
            // Прерываем операцию
            return;
        }

        // Получаем текущую дату
        $currentTimestamp = $this->freshTimestamp();

        // Получаем имя атрибута даты создания модели
        $createDateAttributeName = $this->getCreatedAtColumn();
        // Получаем имя атрибута даты изменения модели
        $changeDateAttributeName = $this->getUpdatedAtColumn();

        // Проверяем, указано ли имя атрибута даты создания модели
        if (!is_null($createDateAttributeName) AND $createDateAttributeName != '')
        {
            // Имя атрибута даты создания модели указано
            // Проверяем, является ли значение атрибута даты создания модели пустым
            if (empty($this->getAttribute($createDateAttributeName)))
            {
                // Значение атрибута даты создания модели является пустым
                // Устанавливаем текущую дату, как значение по умолчанию для атрибута даты создания модели
                $this->setAttribute($createDateAttributeName, $currentTimestamp);
            }
        }

        // Проверяем, указано ли имя атрибута даты изменения модели
        if (!is_null($changeDateAttributeName) AND $changeDateAttributeName != '')
        {
            // Имя атрибута даты изменения модели указано
            // Проверяем, является ли значение атрибута даты изменения модели пустым
            if (empty($this->getAttribute($changeDateAttributeName)))
            {
                // Значение атрибута даты изменения модели является пустым
                // Устанавливаем текущую дату, как значение по умолчанию для атрибута даты изменения модели
                $this->setAttribute($changeDateAttributeName, $currentTimestamp);
            }
        }
    }

    /**
     * Получает валидируемые атрибуты модели
     *
     * @return array
     */
    private function getValidatedAttributes() : array
    {
        // Получаем список видимых атрибутов модели
        $visibleAttributes = $this->getVisible();
        // Получаем список скрытых атрибутов модели
        $hiddenAttributes = $this->getHidden();
        // Сбрасываем список видимых атрибутов модели
        $this->setVisible(array());
        // Сбрасываем список скрытых атрибутов модели
        $this->setHidden(array());

        // Пытаемся получить список атрибутов модели без видимости атрибутов
        try
        {
            // Получаем список атрибутов модели без видимости атрибутов
            $attributes = $this->attributesToArray();
        }
        finally
        {
            // Возвращаем сохранённый список видимых атрибутов модели
            $this->setVisible($visibleAttributes);
            // Возвращаем сохранённый список скрытых атрибутов модели
            $this->setHidden($hiddenAttributes);
        }

        return $attributes;
    }

    /**
     * Получает правила валидации модели
     *
     * @return array
     */
    protected function getValidationRules() : array
    {
        // Возвращаем пустой список правил валидации модели
        // Метод должен быть переопределён в модели наследнике
        return array();
    }

    /**
     * Получает правила валидации модели перед удалением
     *
     * @return array
     */
    protected function getDeletionValidationRules() : array
    {
        // Возвращаем пустой список правил валидации модели перед удалением
        // Метод должен быть переопределён в модели наследнике
        return array();
    }

    /**
     * Получает пользовательские сообщения об ошибках валидации модели
     *
     * @return array
     */
    protected function getCustomValidationErrorMessages() : array
    {
        // Возвращаем пустой список пользовательских сообщений об ошибках валидации модели
        // Метод должен быть переопределён в модели наследнике
        return array();
    }

    /**
     * Получает пользовательские сообщения об ошибках валидации модели перед удалением
     *
     * @return array
     */
    protected function getCustomDeletionValidationErrorMessages() : array
    {
        // Возвращаем пустой список пользовательских сообщений об ошибках валидации модели перед удалением
        // Метод должен быть переопределён в модели наследнике
        return array();
    }

    /**
     * Получает имена атрибутов модели
     *
     * @return array
     */
    protected function getAttributesNames() : array
    {
        // Возвращаем пустой список имён атрибутов модели
        // Метод должен быть переопределён в модели наследнике
        return array();
    }

    /**
     * Выполняет операции перед созданием модели
     *
     * @return void
     */
    protected function beforeCreate() : void
    {
        // Метод не выполняет никаких действий
        // Метод должен быть переопределён в модели наследнике
    }

    /**
     * Выполняет операции перед обновлением модели
     *
     * @return void
     */
    protected function beforeUpdate() : void
    {
        // Метод не выполняет никаких действий
        // Метод должен быть переопределён в модели наследнике
    }

    /**
     * Выполняет операции перед удалением модели
     *
     * @return void
     */
    protected function beforeDelete() : void
    {
        // Метод не выполняет никаких действий
        // Метод должен быть переопределён в модели наследнике
    }

    /**
     * Выполняет валидацию только изменённых атрибутов модели
     *
     * @return void
     * @throws ValidationException Модель не валидна
     */
    final public function validateChanges() : void
    {
        // Создаём пустой список правил валидации только изменённых атрибутов модели
        $changedAttributesValidationRules = array();
        // Получаем правила валидации модели
        $validationRules = $this->getValidationRules();

        // Проходимся по полученному списку правил валидации модели
        foreach ($validationRules as $attributeName => $validationRule)
        {
            // Проверяем, является ли атрибут модели с текущим именем изменённым
            if ($this->isDirty($attributeName))
            {
                // Атрибут модели с текущим именем является изменённым
                // Добавляем правила валидации для атрибута модели с текущим именем
                // в список правил валидации только изменённых атрибутов модели
                $changedAttributesValidationRules[$attributeName] = $validationRule;
            }
        }

        // Создаём валидатор с полученным списком правил валидации только изменённых атрибутов модели
        // Выполняем валидацию только изменённых атрибутов модели
        // Указываем список пользовательских сообщений об ошибках валидации модели
        // Указываем список имён атрибутов модели для построения сообщений об ошибках
        $validator = Validator::make($this->getValidatedAttributes(), $changedAttributesValidationRules,
            $this->getCustomValidationErrorMessages(), $this->getAttributesNames());

        // Проверяем, есть ли ошибки при валидации только изменённых атрибутов модели
        if ($validator->fails())
        {
            // При валидации только изменённых атрибутов модели есть ошибки
            // Выбрасываем исключение
            throw new ValidationException($validator);
        }
    }

    /**
     * Выполняет валидацию модели
     *
     * @param bool $isDelete Показатель, нужно ли выполнять валидацию модели перед удалением
     * @return void
     * @throws ValidationException Модель не валидна
     */
    final public function validate(bool $isDelete = false) : void
    {
        // Получаем правила валидации модели
        // Если указано, то получаем правила валидации модели перед удалением
        $validationRules = $isDelete ? $this->getDeletionValidationRules() : $this->getValidationRules();
        // Получаем пользовательские сообщения об ошибках валидации модели
        // Если указано, то получаем пользовательские сообщения об ошибках валидации модели перед удалением
        $customValidationErrorMessages = $isDelete ? $this->getCustomDeletionValidationErrorMessages()
            : $this->getCustomValidationErrorMessages();
        // Создаём валидатор с полученным списком правил валидации модели
        // Выполняем валидацию модели
        // Указываем список пользовательских сообщений об ошибках валидации модели
        // Указываем список имён атрибутов модели для построения сообщений об ошибках
        $validator = Validator::make($this->getValidatedAttributes(), $validationRules,
            $customValidationErrorMessages, $this->getAttributesNames());

        // Проверяем, есть ли ошибки при валидации модели
        if ($validator->fails())
        {
            // При валидации модели есть ошибки
            // Выбрасываем исключение
            throw new ValidationException($validator);
        }
    }

    /**
     * Отменяет изменение значений указанных атрибутов модели
     *
     * @param string|array $attributes Атрибут или список атрибутов модели
     * @return void
     */
    final public function cancelChanges($attributes) : void
    {
        // Получаем список атрибутов модели, изменение значений которых нужно отменить
        // Проверяем, если передан список атрибутов модели, то он и будет являться списком атрибутов модели,
        // изменение значений которых нужно отменить
        // Если передан один атрибут модели, то создаём список атрибутов модели,
        // изменение значений которых нужно отменить, с одним переданным атрибутом модели
        $cancelChangesAttributes = is_array($attributes) ? $attributes : [ $attributes ];

        // Проходимся по полученному списку атрибутов модели, изменение значений которых нужно отменить
        foreach ($cancelChangesAttributes as $attributeName)
        {
            // Проверяем, является ли атрибут модели с текущим именем изменённым
            if ($this->isDirty($attributeName))
            {
                // Атрибут модели с текущим именем является изменённым
                // Проверяем, существует ли модель в базе данных
                if ($this->exists)
                {
                    // Модель существует в базе данных
                    // Сохраняем атрибуту модели с текущим именем его оригинальное значение,
                    // то есть отменяем изменение значения атрибута модели с текущим именем
                    $this->$attributeName = $this->getOriginal($attributeName);
                }
                else
                {
                    // Модели не существует в базе данных
                    // У атрибута модели с текущим именем нет оригинального значения
                    // Удаляем атрибут с текущим именем у модели,
                    // то есть отменяем изменение значения атрибута модели с текущим именем
                    unset($this->$attributeName);
                }
            }
        }
    }
}
