<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Класс для создания данных в базе данных
 *
 * @package Database\Seeders
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database
     *
     * @return void
     */
    public function run()
    {
        // Список классов для создания данных в базе данных, инструкции которых нужно выполнить
        $executableClasses = [ TaskStatusesSeeder::class ];
        // Выполняем инструкции классов для создания данных в базе данных
        $this->call($executableClasses);
    }
}
