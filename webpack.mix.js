const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

// Собираем все JavaScript файлы проекта в один файл и переводим в ES2015 синтаксис
mix.babel(
    [
        'resources/js/system.js',
        'resources/js/api_routes.js',
        'resources/js/tasks_manager.js',
        'resources/js/html_document.js'
    ],
    'public/js/app.js');
