<?php

namespace App\Services\Tasks;

use App\Exceptions\Classes\ModelNotFoundException;
use App\Models\Tasks\Task;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Сервис для работы с задачами
 *
 * @package Services\Tasks
 * @method static TasksService getInstance() Получает экземпляр сервиса для работы с задачами
 */
class TasksService extends Service
{
    /**
     * Получает список моделей задач
     *
     * @param int $statusId ID статуса
     * @param bool|null $isCompleted Показатель, была ли выполнена задача
     * @return Collection
     */
    public function getList(int $statusId = 0, bool $isCompleted = null) : Collection
    {
        // Создаём конструктор запроса к базе данных для получения списка моделей задач
        $builder = Task::query()->with(['relatedStatus'])
            ->orderBy('updated_at', 'DESC');

        // Проверяем, был ли передан ID статуса
        if ($statusId > 0)
        {
            // ID статуса был передан
            // Добавляем условие фильтрации по переданному ID статуса в конструктор запроса к базе данных
            // для получения списка моделей задач
            $builder->where('status_id', '=', $statusId);
        }

        // Проверяем, был ли передан показатель, была ли выполнена задача
        if (!is_null($isCompleted))
        {
            // Показатель, была ли выполнена задача, был передан
            // Добавляем условие фильтрации по переданному показателю, была ли выполнена задача,
            // в конструктор запроса к базе данных для получения списка моделей задач
            $builder->where('is_completed', '=', $isCompleted);
        }

        // Возвращаем список моделей задач с переданными параметрами, полученный из базы данных
        return $builder->get();
    }

    /**
     * Получает модель задачи
     *
     * @param int $id ID
     * @return Task|Model|null
     */
    public function get(int $id)
    {
        // Получаем модель задачи с переданным ID из базы данных
        // Возвращаем полученную из базы данных модель задачи с переданным ID
        return Task::query()->find($id);
    }

    /**
     * Получает детальную модель задачи
     *
     * @param int $id ID
     * @return Task|Model|null
     */
    public function getDetailed(int $id)
    {
        // Получаем модель задачи с переданным ID из базы данных
        // Подгружаем данные о связанном с моделью задачи с переданным ID статусе
        // Возвращаем полученную из базы данных детальную модель задачи с переданным ID
        return Task::query()->with(['relatedStatus'])->find($id);
    }

    /**
     * Получает количество задач
     *
     * @return int
     */
    public function getCount() : int
    {
        // Возвращаем количество задач, полученное из базы данных
        return Task::query()->count();
    }

    /**
     * Получает количество активных задач
     *
     * @return int
     */
    public function getActiveCount() : int
    {
        // Возвращаем количество невыполненных задач, полученное из базы данных
        return Task::query()
            ->where('is_completed', '=', false)
            ->count();
    }

    /**
     * Создаёт модель задачи
     *
     * @param array $data Данные для создания модели задачи
     * @return Task
     */
    public function create(array $data) : Task
    {
        // Создаём модель задачи с переданными данными для создания
        $task = new Task($data);
        // Добавляем созданную модель задачи в базу данных
        $task->save();
        // Возвращаем созданную модель задачи
        return $task;
    }

    /**
     * Изменяет модель задачи
     *
     * @param int $id ID
     * @param array $data Данные для изменения модели задачи
     * @return Task
     * @throws ModelNotFoundException Модель задачи не найдена
     */
    public function change(int $id, array $data) : Task
    {
        // Получаем модель задачи с переданным ID
        $task = $this->get($id);

        // Проверяем, была ли получена модель задачи с переданным ID
        if (is_null($task))
        {
            // Модель задачи с переданным ID не была получена
            // Выбрасываем исключение
            throw new ModelNotFoundException('Модель задачи не найдена', $id, Task::class);
        }

        // Изменяем данные полученной модели задачи на переданные для изменения данные
        $task->fill($data);
        // Сохраняем изменения модели задачи в базе данных
        $task->save();
        // Возвращаем изменённую модель задачи
        return $task;
    }

    /**
     * Изменяет состояние модели задачи
     *
     * @param int $id ID
     * @param bool $isCompleted Показатель, была ли выполнена задача
     * @return Task
     * @throws ModelNotFoundException Модель задачи не найдена
     */
    public function changeState(int $id, bool $isCompleted) : Task
    {
        // Получаем модель задачи с переданным ID
        $task = $this->get($id);

        // Проверяем, была ли получена модель задачи с переданным ID
        if (is_null($task))
        {
            // Модель задачи с переданным ID не была получена
            // Выбрасываем исключение
            throw new ModelNotFoundException('Модель задачи не найдена', $id, Task::class);
        }

        // Изменяем состояние полученной модели задачи на переданный показатель, была ли выполнена задача
        $task->is_completed = $isCompleted;
        // Сохраняем изменения модели задачи в базе данных
        $task->save();
        // Возвращаем модель задачи с изменённым состоянием
        return $task;
    }

    /**
     * Удаляет модель задачи
     *
     * @param int $id ID
     * @return bool
     * @throws ModelNotFoundException Модель задачи не найдена
     */
    public function delete(int $id) : bool
    {
        // Получаем модель задачи с переданным ID
        $task = $this->get($id);

        // Проверяем, была ли получена модель задачи с переданным ID
        if (is_null($task))
        {
            // Модель задачи с переданным ID не была получена
            // Выбрасываем исключение
            throw new ModelNotFoundException('Модель задачи не найдена', $id, Task::class);
        }

        // Удаляем полученную модель задачи из базы данных
        // Возвращаем логическое значение, была ли удалена модель задачи из базы данных
        return $task->delete();
    }
}
