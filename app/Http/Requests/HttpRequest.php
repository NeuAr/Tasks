<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Класс HTTP-запроса
 *
 * @package App\Http\Requests
 */
class HttpRequest extends FormRequest
{
    /**
     * Определяет, имеет ли пользователь права для выполнения HTTP-запроса
     *
     * @return bool
     */
    public function authorize() : bool
    {
        // Данный HTTP-запрос не реализует правил авторизации пользователей
        // Метод может быть переопределён в HTTP-запросе наследнике
        // Возвращаем истину
        return true;
    }

    /**
     * Получает правила валидации данных HTTP-запроса
     *
     * @return array
     */
    public function rules() : array
    {
        // Данный HTTP-запрос не реализует правил валидации данных
        // Метод может быть переопределён в HTTP-запросе наследнике
        // Возвращаем пустой список правил валидации данных HTTP-запроса
        return [];
    }

    /**
     * Получает целочисленные данные с указанным ключом из HTTP-запроса
     *
     * @param string $key Ключ данных
     * @param int $default Значение по умолчанию
     * @return int
     */
    public function getIntValue(string $key, int $default = 0) : int
    {
        // Получаем данные с переданным ключом из HTTP-запроса
        // Если данные с переданным ключом из HTTP-запроса не были получены, возвращаем переданное значение по умолчанию
        // Приводим тип полученных данных к целому числу
        // Возвращаем целочисленные данные с переданным ключом из HTTP-запроса
        return intval($this->input($key, $default));
    }

    /**
     * Получает целочисленные неотрицательные данные с указанным ключом из HTTP-запроса
     *
     * @param string $key Ключ данных
     * @param int $default Значение по умолчанию
     * @return int
     */
    public function getUnsignedIntValue(string $key, int $default = 0) : int
    {
        // Проверяем, является ли переданное значение по умолчанию отрицательным числом
        // Если переданное значение по умолчанию является отрицательным числом, сохраняем 0, как значение по умолчанию
        $default = $default < 0 ? 0 : $default;
        // Получаем целочисленные данные с переданным ключом из HTTP-запроса
        $data = $this->getIntValue($key, $default);
        // Проверяем, являются ли полученные целочисленные данные с переданным ключом из HTTP-запроса
        // отрицательным числом
        // Если полученные целочисленные данные с переданным ключом из HTTP-запроса являются отрицательным числом,
        // возвращаем переданное значение по умолчанию
        // Возвращаем целочисленные неотрицательные данные с переданным ключом из HTTP-запроса
        return $data < 0 ? $default : $data;
    }

    /**
     * Получает целочисленные положительные данные с указанным ключом из HTTP-запроса
     *
     * @param string $key Ключ данных
     * @param int $default Значение по умолчанию
     * @return int
     */
    public function getPositiveIntValue(string $key, int $default = 1) : int
    {
        // Проверяем, является ли переданное значение по умолчанию положительным числом
        // Если переданное значение по умолчанию не является положительным числом,
        // сохраняем 1, как значение по умолчанию
        $default = $default <= 0 ? 1 : $default;
        // Получаем целочисленные данные с переданным ключом из HTTP-запроса
        $data = $this->getIntValue($key, $default);
        // Проверяем, являются ли полученные целочисленные данные с переданным ключом из HTTP-запроса
        // положительным числом
        // Если полученные целочисленные данные с переданным ключом из HTTP-запроса не являются положительным числом,
        // возвращаем переданное значение по умолчанию
        // Возвращаем целочисленные положительные данные с переданным ключом из HTTP-запроса
        return $data <= 0 ? $default : $data;
    }
}