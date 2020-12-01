'use strict';

/**
 * Менеджер задач
 */
class TasksManager
{
    /**
     * Создаёт экземпляр класса TasksManager
     */
    constructor()
    {
        /**
         * Показатель, заблокирован ли менеджер задач
         *
         * @type {boolean}
         */
        this.isLocked = false;

        /**
         * JQuery объект главного контейнера
         *
         * @type {*|jQuery|HTMLElement}
         */
        this.jGlobalBox = $('.tasks-page-box');

        /**
         * JQuery объект контейнера задач
         *
         * @type {*|jQuery|HTMLElement}
         */
        this.jTasksBox = $('.tasks-box');

        /**
         * JQuery объект контейнера шаблона задачи
         *
         * @type {*|jQuery|HTMLElement}
         */
        this.jTaskTemplateBox = $('.task-box.template');

        /**
         * CSRF токен
         *
         * @type {string}
         */
        this.token = this.jGlobalBox.attr('token');

        /**
         * JQuery объект контейнера общего количества задач
         *
         * @type {*|jQuery|HTMLElement}
         */
        this.jTasksTotalCountBox = $('.tasks-statistics-box span.total-count');

        /**
         * JQuery объект контейнера количества активных задач
         *
         * @type {*|jQuery|HTMLElement}
         */
        this.jTasksActiveCountBox = $('.tasks-statistics-box span.active-count');

        /**
         * JQuery объект списков фильтров
         *
         * @type {*|jQuery|HTMLElement}
         */
        this.jFilterSelects = $('.tasks-filter-box select.filter-select');

        /**
         * JQuery объект фильтра по статусу задачи
         *
         * @type {*|jQuery|HTMLElement}
         */
        this.jStatusTasksFilter = $('#status-filter');

        /**
         * JQuery объект фильтра по состоянию задачи
         *
         * @type {*|jQuery|HTMLElement}
         */
        this.jCompletedTasksFilter = $('#completed-filter');

        /**
         * JQuery объект формы редактирования задачи
         *
         * @type {*|jQuery|HTMLElement}
         */
        this.jTaskEditForm = $('.task-edit-form');

        /**
         * JQuery объект кнопки сохранения формы редактирования задачи
         *
         * @type {*|jQuery|HTMLElement}
         */
        this.jTaskEditFormSaveButton = $('.task-edit-form a.save-button');

        /**
         * JQuery объект поля формы редактирования задачи для изменения статуса задачи
         *
         * @type {*|jQuery|HTMLElement}
         */
        this.jChangeTaskStatusField = $('#task-status-field');

        /**
         * JQuery объект поля формы редактирования задачи для изменения текста задачи
         *
         * @type {*|jQuery|HTMLElement}
         */
        this.jChangeTaskTextField = $('#task-text-field');

        /**
         * JQuery объект контейнера контроллера создания задачи
         *
         * @type {*|jQuery|HTMLElement}
         */
        this.jCreateTaskBox = $('.create-task-box');

        let tasksManager = this;

        this.jFilterSelects.change(function()
        {
            tasksManager.updateTasksList();
        });

        this.jCreateTaskBox.find('a').click(function()
        {
            tasksManager.showTaskCreateForm();
            return false;
        });

        this.jTaskEditForm.find('a.save-button').click(function()
        {
            if ($(this).attr('element-id') <= 0)
            {
                tasksManager.createTask();
            }
            else
            {
                tasksManager.changeTask($(this).attr('element-id'));
            }

            return false;
        });

        this.jTaskEditForm.find('a.cancel-button').click(function()
        {
            tasksManager.closeTaskEditForm();
            return false;
        });

        $('.task-box a.completed-button').click(function()
        {
            tasksManager.makeCompletedTask($(this).attr('element-id'));
            return false;
        });

        $('.task-box a.not-completed-button').click(function()
        {
            tasksManager.makeNotCompletedTask($(this).attr('element-id'));
            return false;
        });

        $('.task-box a.change-button').click(function()
        {
            tasksManager.showTaskChangeForm($(this).attr('element-id'));
            return false;
        });

        $('.task-box a.remove-button').click(function()
        {
            tasksManager.deleteTask($(this).attr('element-id'));
            return false;
        });
    }

    /**
     * Создаёт объект менеджера задач
     *
     * @returns {TasksManager}
     */
    static create()
    {
        return new TasksManager();
    }

    /**
     * Блокирует менеджера задач
     *
     * @returns {boolean}
     */
    lock()
    {
        if (this.isLocked)
        {
            return false;
        }

        this.isLocked = true;
        this.jGlobalBox.addClass('disabled');
        this.jFilterSelects.prop('disabled', true);

        return true;
    }

    /**
     * Разблокирует менеджера задач
     */
    unlock()
    {
        this.jGlobalBox.removeClass('disabled');
        this.jFilterSelects.prop('disabled', false);
        this.isLocked = false;
    }

    /**
     * Получает JQuery объект контейнера задачи
     *
     * @param {number} id ID задачи
     * @returns {*|jQuery|HTMLElement}
     */
    getTaskBox(id)
    {
        return $('#task-box-' + id);
    }

    /**
     * Создаёт контейнер задачи
     *
     * @param {any} task Объект задачи
     * @returns {*|jQuery|HTMLElement}
     */
    createTaskBox(task)
    {
        let taskBox = this.jTaskTemplateBox.clone(true, true);
        taskBox.removeClass('template');
        taskBox.addClass(task.is_completed ? 'completed' : 'active');
        taskBox.css('background-color', task.related_status.color);
        taskBox.attr('status-id', task.status_id);
        taskBox.attr('id', 'task-box-' + task.id);
        taskBox.find('.status-box span').text(task.related_status.name);
        taskBox.find('.text-box span').text(task.text);
        taskBox.find('.control-box a.control-element').attr('element-id', task.id);
        return taskBox;
    }

    /**
     * Изменяет данные контейнера задачи
     *
     * @param {any} task Объект задачи
     */
    changeTaskBox(task)
    {
        let taskBox = this.getTaskBox(task.id);
        taskBox.removeClass('completed');
        taskBox.removeClass('active');
        taskBox.addClass(task.is_completed ? 'completed' : 'active');
        taskBox.css('background-color', task.related_status.color);
        taskBox.attr('status-id', task.status_id);
        taskBox.find('.status-box span').text(task.related_status.name);
        taskBox.find('.text-box span').text(task.text);
    }

    /**
     * Удаляет контейнер задачи
     *
     * @param {number} id ID задачи
     */
    removeTaskBox(id)
    {
        let taskBox = this.getTaskBox(id);
        taskBox.addClass('deleted');
        taskBox.text('Задача (' + id + ') удалена');
    }

    /**
     * Получает данные задачи
     *
     * @param {number} id ID задачи
     * @returns {{ statusId: number, text: string }}
     */
    getTaskData(id)
    {
        let taskBox = this.getTaskBox(id);
        return {
            statusId: parseInt(taskBox.attr('status-id')),
            text: taskBox.find('.text-box span').text()
        };
    }

    /**
     * Получает значения полей формы редактирования задачи
     *
     * @returns {{ statusId: number, text: string }}
     */
    getValuesTaskEditForm()
    {
        return {
            statusId: parseInt(this.jChangeTaskStatusField.val()),
            text: this.jChangeTaskTextField.val()
        };
    }

    /**
     * Устанавливает значения полей формы редактирования задачи
     *
     * @param {{ statusId: number, text: string }} data
     */
    setValuesTaskEditForm(data)
    {
        this.jChangeTaskStatusField.val(data.statusId);
        this.jChangeTaskTextField.val(data.text);
    }

    /**
     * Отчищает значения полей формы редактирования задачи
     */
    clearValuesTaskEditForm()
    {
        this.setValuesTaskEditForm({ statusId: 0, text: '' });
    }

    /**
     * Показывает форму создания задачи
     */
    showTaskCreateForm()
    {
        if (!this.lock())
        {
            return;
        }

        this.clearValuesTaskEditForm();
        this.jTaskEditFormSaveButton.attr('element-id', 0);
        this.jCreateTaskBox.after(this.jTaskEditForm);
        this.jTaskEditForm.css('display', 'block');

        this.unlock();
    }

    /**
     * Показывает форму изменения задачи
     *
     * @param {number} id ID задачи
     */
    showTaskChangeForm(id)
    {
        if (!this.lock())
        {
            return;
        }

        let taskData = this.getTaskData(id);
        let taskBox = this.getTaskBox(id);

        this.setValuesTaskEditForm(taskData);
        this.jTaskEditFormSaveButton.attr('element-id', id);
        taskBox.after(this.jTaskEditForm);
        this.jTaskEditForm.css('display', 'block');

        this.unlock();
    }

    /**
     * Скрывает форму редактирования задачи
     */
    hiddenTaskEditForm()
    {
        this.jTaskEditForm.css('display', 'none');
        this.jCreateTaskBox.after(this.jTaskEditForm);
    }

    /**
     * Закрывает форму редактирования задачи
     */
    closeTaskEditForm()
    {
        if (!this.lock())
        {
            return;
        }

        this.hiddenTaskEditForm();
        this.unlock();
    }

    /**
     * Обновляет статистику задач
     */
    updateTasksStatistics()
    {
        let tasksManager = this;

        httpGet(ApiRoutes.getTasksStatistics(), function(response)
            {
                tasksManager.jTasksTotalCountBox.text(response.count);
                tasksManager.jTasksActiveCountBox.text(response.active_count);
            });
    }

    /**
     * Обновляет список задач
     */
    updateTasksList()
    {
        if (!this.lock())
        {
            return;
        }

        let tasksManager = this;
        let filterStatusId = this.jStatusTasksFilter.val();
        let filterCompleted = this.jCompletedTasksFilter.val();

        httpGet(ApiRoutes.getTasksList(filterStatusId, filterCompleted), function(response)
            {
                tasksManager.hiddenTaskEditForm();
                tasksManager.jTasksBox.html('');

                for (let index = 0; index < response.length; ++index)
                {
                    let taskBox = tasksManager.createTaskBox(response[index]);
                    tasksManager.jTasksBox.append(taskBox);
                }
            },
            function()
            {
                tasksManager.unlock();
            });
    }

    /**
     * Получает данные HTTP-запроса для редактирования задачи
     *
     * @returns {string}
     */
    getEditTaskHttpData()
    {
        let taskChangedData = this.getValuesTaskEditForm();
        return 'status_id=' + taskChangedData.statusId + '&text=' + encodeURIComponent(taskChangedData.text);
    }

    /**
     * Получает данные HTTP-запроса для действия с задачей
     *
     * @param {string} method Метод
     * @param {boolean} isAddEditTaskData Показатель, добавлять ли данные HTTP-запроса для редактирования задачи
     * @returns {string}
     */
    getTaskActionHttpData(method = 'POST', isAddEditTaskData = false)
    {
        return '_token=' + this.token + '&_method=' + method +
            (isAddEditTaskData ? '&' + this.getEditTaskHttpData() : '');
    }

    /**
     * Отправляет HTTP-запрос для действия с задачей
     *
     * @param {string} url URL
     * @param {any} successCallback Функция обратного вызова при успешной отправке HTTP-запроса
     * @param {string} method Метод
     * @param {boolean} isAddEditTaskData Показатель, добавлять ли данные HTTP-запроса для редактирования задачи
     */
    sendTaskActionHttpRequest(url, successCallback, method = 'POST', isAddEditTaskData = false)
    {
        if (!this.lock())
        {
            return;
        }

        let tasksManager = this;

        httpPost(url, this.getTaskActionHttpData(method, isAddEditTaskData), function(response)
            {
                successCallback(response, tasksManager);
            },
            function()
            {
                tasksManager.unlock();
            });
    }

    /**
     * Создаёт задачу
     */
    createTask()
    {
        this.sendTaskActionHttpRequest(ApiRoutes.createTask(), function(response, tasksManager)
            {
                let taskBox = tasksManager.createTaskBox(response);
                tasksManager.jTasksBox.prepend(taskBox);
                tasksManager.updateTasksStatistics();
                tasksManager.clearValuesTaskEditForm();
            },
            'POST',
            true);
    }

    /**
     * Изменяет задачу
     *
     * @param {number} id ID задачи
     */
    changeTask(id)
    {
        this.sendTaskActionHttpRequest(ApiRoutes.updateTask(id), function(response, tasksManager)
            {
                tasksManager.changeTaskBox(response);
                tasksManager.hiddenTaskEditForm();
            },
            'PUT',
            true);
    }

    /**
     * Удаляет задачу
     *
     * @param {number} id ID задачи
     */
    deleteTask(id)
    {
        this.sendTaskActionHttpRequest(ApiRoutes.deleteTask(id), function(response, tasksManager)
            {
                tasksManager.removeTaskBox(id);
                tasksManager.updateTasksStatistics();
                tasksManager.hiddenTaskEditForm();
            },
            'DELETE');
    }

    /**
     * Делает задачу выполненной
     *
     * @param {number} id ID задачи
     */
    makeCompletedTask(id)
    {
        this.sendTaskActionHttpRequest(ApiRoutes.makeCompletedTask(id), function(response, tasksManager)
            {
                tasksManager.changeTaskBox(response);
                tasksManager.updateTasksStatistics();
                tasksManager.hiddenTaskEditForm();
            },
            'PATCH');
    }

    /**
     * Делает задачу невыполненной
     *
     * @param {number} id ID задачи
     */
    makeNotCompletedTask(id)
    {
        this.sendTaskActionHttpRequest(ApiRoutes.makeNotCompletedTask(id),
            function(response, tasksManager)
            {
                tasksManager.changeTaskBox(response);
                tasksManager.updateTasksStatistics();
                tasksManager.hiddenTaskEditForm();
            },
            'PATCH');
    }
}
