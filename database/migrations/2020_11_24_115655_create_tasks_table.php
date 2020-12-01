<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Миграция для создания таблицы базы данных задач
 */
class CreateTasksTable extends Migration
{
    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedTinyInteger('status_id');
            $table->string('text', 4000);
            $table->boolean('is_completed');
            // Индексы и ограничения
            $table->foreign('status_id')
                ->references('id')
                ->on('task_statuses')
                ->onUpdate('RESTRICT')
                ->onDelete('RESTRICT');
            $table->index('status_id');
            $table->index('updated_at');
            $table->index(['status_id', 'updated_at']);
            $table->index(['is_completed', 'updated_at']);
            $table->index(['status_id', 'is_completed', 'updated_at']);
        });
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
