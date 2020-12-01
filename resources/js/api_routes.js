'use strict';

/**
 * Класс маршрутов API
 */
class ApiRoutes
{
    /**
     * Получает маршрут для получения статистики задач
     *
     * @return {string}
     */
    static getTasksStatistics()
    {
        return 'api/tasks/statistics';
    }

    /**
     * Получает маршрут для получения списка задач
     *
     * @param {number} statusId ID статуса задачи
     * @param {number} completed Состояние задачи
     * @return {string}
     */
    static getTasksList(statusId = 0, completed= 0)
    {
        return 'api/tasks?status_id=' + statusId + '&is_completed=' + completed;
    }

    /**
     * Получает маршрут для создания задачи
     *
     * @return {string}
     */
    static createTask()
    {
        return 'api/tasks';
    }

    /**
     * Получает маршрут для изменения задачи
     *
     * @param {number} id ID задачи
     * @return {string}
     */
    static updateTask(id)
    {
        return 'api/tasks/' + id;
    }

    /**
     * Получает маршрут для удаления задачи
     *
     * @param {number} id ID задачи
     * @return {string}
     */
    static deleteTask(id)
    {
        return 'api/tasks/' + id;
    }

    /**
     * Получает маршрут для изменения состояния задачи на завершённое
     *
     * @param {number} id ID задачи
     * @return {string}
     */
    static makeCompletedTask(id)
    {
        return 'api/tasks/' + id + '/completed';
    }

    /**
     * Получает маршрут для изменения состояния задачи на незавершённое
     *
     * @param {number} id ID задачи
     * @return {string}
     */
    static makeNotCompletedTask(id)
    {
        return 'api/tasks/' + id + '/not_completed';
    }
}
