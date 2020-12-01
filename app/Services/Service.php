<?php

namespace App\Services;

/**
 * Класс сервиса
 *
 * @package App\Services
 */
abstract class Service
{
    /**
     * Реестр экземпляров сервисов
     *
     * @var array
     */
    private static $instancesRegistry = array();

    /**
     * Создаёт экземпляр класса {@see Service}
     */
    protected function __construct()
    {
        // Делаем конструктор всех сервисов защищённым
        // Создать экземпляр класса сервиса можно только через специальный метод
    }

    /**
     * Получает экземпляр сервиса
     *
     * @return mixed
     */
    public static function getInstance()
    {
        // Получаем имя текущего сервиса
        $serviceName = static::class;

        // Проверяем, есть ли экземпляр текущего сервиса в реестре экземпляров сервисов
        if (!isset(self::$instancesRegistry[$serviceName]))
        {
            // Экземпляра текущего сервиса нет в реестре экземпляров сервисов
            // Создаём экземпляр текущего сервиса в реестре экземпляров сервисов
            self::$instancesRegistry[$serviceName] = new static();
        }

        // Возвращаем экземпляр текущего сервиса
        return self::$instancesRegistry[$serviceName];
    }
}
