<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Класс для создания статусов задач в базе данных
 *
 * @package Database\Seeders
 */
class TaskStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds
     *
     * @return void
     */
    public function run()
    {
        // Создаём статусы заказов в базе данных
        DB::table('task_statuses')
            ->insert(
                [
                    [ 'id' => 1, 'name' => 'Обычная задача', 'color' => 'White' ],
                    [ 'id' => 2, 'name' => 'Важная задача', 'color' => 'LemonChiffon' ],
                    [ 'id' => 3, 'name' => 'Критическая задача', 'color' => 'LightSalmon' ]
                ]
            );
    }
}
