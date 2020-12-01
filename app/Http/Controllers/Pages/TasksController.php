<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Services\Tasks\TasksService;
use App\Services\Tasks\TaskStatusesService;
use Illuminate\Contracts\View\View;

/**
 * Контроллер для работы с задачами
 *
 * @package App\Http\Controllers\Pages
 */
class TasksController extends Controller
{
    /**
     * Получает страницу списка задач
     *
     * @return View
     */
    public function index() : View
    {
        // Получаем список моделей задач
        $tasks = TasksService::getInstance()->getList();
        // Возвращаем представление страницы списка задач
        return view('tasks.list',
            [
                'tasks' => $tasks,
                'taskStatuses' => TaskStatusesService::getInstance()->getList(),
                'totalCount' => TasksService::getInstance()->getCount(),
                'activeCount' => TasksService::getInstance()->getActiveCount()
            ]);
    }
}
