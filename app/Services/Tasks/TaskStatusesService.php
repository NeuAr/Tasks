<?php

namespace App\Services\Tasks;

use App\Models\Tasks\TaskStatus;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

/**
 * Сервис для работы со статусами задач
 *
 * @package App\Services\Tasks
 * @method static TaskStatusesService getInstance() Получает экземпляр сервиса для работы со статусами задач
 */
class TaskStatusesService extends Service
{
    /**
     * Получает список моделей статусов задач
     *
     * @return Collection
     */
    public function getList() : Collection
    {
        // Возвращаем список моделей статусов задач, полученный из базы данных
        return TaskStatus::query()
            ->orderBy('id', 'ASC')
            ->get();
    }
}
