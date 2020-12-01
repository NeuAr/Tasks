@extends('layouts.layout')

@section('title', 'Мой список задач')
@section('content')
    <div class="tasks-page-box" token="{{ csrf_token() }}">
        <div class="tasks-filter-box">
            <div class="content">
                <div class="row">
                    <div class="select-box">
                        <div class="label">Статус:</div>
                        <select class="filter-select" id="status-filter">
                            <option value="0">Все</option>
                            @foreach ($taskStatuses as $taskStatus)
                                <option value="{{ $taskStatus->id }}">{{ $taskStatus->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="select-box padding-left-10">
                        <div class="label">Состояние:</div>
                        <select class="filter-select" id="completed-filter">
                            <option value="0">Все</option>
                            <option value="1">Только активные</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="tasks-statistics-box">
            Всего задач: <span class="total-count">{{ $totalCount }}</span>, активных задач <span class="active-count">{{ $activeCount }}</span>
        </div>
        <div class="create-task-box">
            <a href="" class="control-element" title="Создать задачу"><i class="fa fa-plus"></i>&nbsp;&nbsp;Создать задачу</a>
        </div>
        <div class="task-edit-form">
            <div class="form-field-box">
                <div class="label">Статус:</div>
                <select id="task-status-field">
                    <option value="0">Выбрать</option>
                    @foreach ($taskStatuses as $taskStatus)
                        <option value="{{ $taskStatus->id }}">{{ $taskStatus->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-field-box">
                <div class="label">Текст:</div>
                <textarea id="task-text-field" rows="2"></textarea>
            </div>
            <div class="row">
                <a href="" class="cancel-button" title="Отменить изменения">
                    <i class="fa fa-remove"></i>
                </a>
                <a href="" class="save-button" title="Сохранить изменения" element-id="0">
                    <i class="fa fa-save"></i>
                </a>
            </div>
        </div>
        <div class="task-box template" id="task-box-0" status-id="0">
            <div class="status-box">
                <span></span>
            </div>
            <div class="text-box">
                <span></span>
            </div>
            <div class="control-box">
                <a href="" class="control-element completed-button"
                   title="Отметить задачу выполненной" element-id="0">
                    <i class="fa fa-check-square-o"></i>
                </a>
                <a href="" class="control-element not-completed-button"
                   title="Отметить задачу невыполненной" element-id="0">
                    <i class="fa fa-ban"></i>
                </a>
                <a href="" class="control-element change-button"
                   title="Изменить задачу" element-id="0">
                    <i class="fa fa-edit"></i>
                </a>
                <a href="" class="control-element remove-button"
                   title="Удалить задачу" element-id="0">
                    <i class="fa fa-remove"></i>
                </a>
            </div>
        </div>
        <div class="tasks-box">
            @foreach ($tasks as $task)
                <div class="task-box{{ $task->is_completed ? ' completed' : ' active' }}" id="task-box-{{ $task->id }}"
                     status-id="{{ $task->relatedStatus->id }}" style="background-color: {{ $task->relatedStatus->color }};">
                    <div class="status-box">
                        <span>{{ $task->relatedStatus->name }}</span>
                    </div>
                    <div class="text-box">
                        <span>{{ $task->text }}</span>
                    </div>
                    <div class="control-box">
                        <a href="" class="control-element completed-button"
                           title="Отметить задачу выполненной" element-id="{{ $task->id }}">
                            <i class="fa fa-check-square-o"></i>
                        </a>
                        <a href="" class="control-element not-completed-button"
                           title="Отметить задачу невыполненной" element-id="{{ $task->id }}">
                            <i class="fa fa-ban"></i>
                        </a>
                        <a href="" class="control-element change-button"
                           title="Изменить задачу" element-id="{{ $task->id }}">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="" class="control-element remove-button"
                           title="Удалить задачу" element-id="{{ $task->id }}">
                            <i class="fa fa-remove"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
