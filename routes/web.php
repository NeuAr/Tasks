<?php

use App\Http\Controllers\API\TasksController as ApiTasksController;
use App\Http\Controllers\Pages\TasksController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Задачи
 */
// Получает страницу списка задач
Route::get('/', [ TasksController::class, 'index' ])
    ->name('tasks.index');

/**
 * API
 */
Route::name('api.')
    ->prefix('api')
    ->group(function()
    {
        /**
         * Задачи
         */
        Route::name('tasks.')
            ->prefix('tasks')
            ->group(function()
            {
                // Получает список задач
                Route::get('/', [ ApiTasksController::class, 'index' ])
                    ->name('index');
                // Получает статистику по задачам
                Route::get('statistics', [ ApiTasksController::class, 'getStatistics' ])
                    ->name('statistics');
                // Создаёт задачу
                Route::post('/', [ ApiTasksController::class, 'store' ])
                    ->name('store');
                // Изменяет задачу
                Route::put('{taskId}',
                        [ ApiTasksController::class, 'update' ])
                    ->name('update')
                    ->where('taskId', '[0-9]+');
                // Удаляет задачу
                Route::delete('{taskId}',
                        [ ApiTasksController::class, 'destroy' ])
                    ->name('destroy')
                    ->where('taskId', '[0-9]+');
                // Делает задачу выполненной
                Route::patch('{taskId}/completed',
                        [ ApiTasksController::class, 'makeCompleted' ])
                    ->name('completed')
                    ->where('taskId', '[0-9]+');
                // Делает задачу невыполненной
                Route::patch('{taskId}/not_completed',
                        [ ApiTasksController::class, 'makeNotCompleted' ])
                    ->name('notCompleted')
                    ->where('taskId', '[0-9]+');
            });
    });
