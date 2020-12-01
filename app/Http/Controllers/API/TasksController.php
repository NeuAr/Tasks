<?php

namespace App\Http\Controllers\API;

use App\Exceptions\Classes\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\HttpRequest;
use App\Models\Tasks\Task;
use App\Services\Tasks\TasksService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

/**
 * Контроллер для работы с задачами
 *
 * @package App\Http\Controllers\API
 */
class TasksController extends Controller
{
    /**
     * Получает список моделей задач
     *
     * @param HttpRequest $request HTTP-запрос
     * @return Collection
     */
    public function index(HttpRequest $request) : Collection
    {
        // Получаем ID статуса, из переданного HTTP-запроса, для фильтрации списка моделей задач
        $statusId = $request->getUnsignedIntValue('status_id');
        // Получаем показатель, была ли выполнена задача, из переданного HTTP-запроса,
        // для фильтрации списка моделей задач
        $isCompleted = $request->getUnsignedIntValue('is_completed') > 0 ? false : null;
        // Возвращаем список моделей задач с фильтрацией по переданным в переданном HTTP-запросе параметрам
        return TasksService::getInstance()->getList($statusId, $isCompleted);
    }

    /**
     * Получает статистику по задачам
     *
     * @return array
     */
    public function getStatistics() : array
    {
        // Получаем общее количество задач и количество активных задач
        // Возвращаем полученную статистику по задачам
        return
            [
                'count' => TasksService::getInstance()->getCount(),
                'active_count' => TasksService::getInstance()->getActiveCount()
            ];
    }

    /**
     * Создаёт модель задачи
     *
     * @param Request $request HTTP-запрос с данными для создания модели задачи
     * @return Task
     */
    public function store(Request $request) : Task
    {
        // Создаём модель задачи
        // Данные для создания модели задачи берём из переданного HTTP-запроса
        $task = TasksService::getInstance()->create($request->all());
        // Возвращаем созданную модель задачи
        return TasksService::getInstance()->getDetailed($task->id);
    }

    /**
     * Изменяет модель задачи
     *
     * @param Request $request HTTP-запрос с данными для изменения модели задачи
     * @param int $id ID
     * @return Task
     * @throws ModelNotFoundException Модель задачи не найдена
     */
    public function update(Request $request, int $id) : Task
    {
        // Изменяем модель задачи с переданным ID
        // Данные для изменения модели задачи берём из переданного HTTP-запроса
        $task = TasksService::getInstance()->change($id, $request->all());
        // Возвращаем изменённую модель задачи
        return TasksService::getInstance()->getDetailed($task->id);
    }

    /**
     * Удаляет модель задачи
     *
     * @param int $id ID
     * @return void
     * @throws ModelNotFoundException Модель задачи не найдена
     */
    public function destroy(int $id) : void
    {
        // Удаляем модель задачи с переданным ID
        TasksService::getInstance()->delete($id);
    }

    /**
     * Делает модель задачи выполненной
     *
     * @param int $id ID
     * @return Task
     * @throws ModelNotFoundException Модель задачи не найдена
     */
    public function makeCompleted(int $id) : Task
    {
        // Делаем модель задачи с переданным ID выполненной
        $task = TasksService::getInstance()->changeState($id, true);
        // Возвращаем изменённую модель задачи
        return TasksService::getInstance()->getDetailed($task->id);
    }

    /**
     * Делает модель задачи невыполненной
     *
     * @param int $id ID
     * @return Task
     * @throws ModelNotFoundException Модель задачи не найдена
     */
    public function makeNotCompleted(int $id) : Task
    {
        // Делаем модель задачи с переданным ID невыполненной
        $task = TasksService::getInstance()->changeState($id, false);
        // Возвращаем изменённую модель задачи
        return TasksService::getInstance()->getDetailed($task->id);
    }
}
