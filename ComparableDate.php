<?php

/**
 * Created by PhpStorm.
 * User: serj0987
 * Date: 22.10.17
 * Time: 11:31
 */

/**
 * Interface ComparableDate - декларирует методы которые позволят сравнивать даты
 */
interface ComparableDate
{
    const YEAR_KEY = 0;
    const MONTH_KEY = 1;
    const DAY_KEY = 2;

    const HOUR_KEY = 3;
    const MINUTE_KEY = 4;
    const SECOND_KEY = 5;

    /**
     * Сравнить текущую дату с указанной
     * @param ComparableDate $date - дата с которой будет сравниваться текущая дата
     * @return int - 1 если текущая дата больше, 0 если равны, -1 если меньше
     */
    public function compare(ComparableDate $date): int;

    /**
     * Получить разобранную дату ввиде массива
     *
     * @return array - [static::YEAR_KEY => <год>, static::MONTH_KEY => <месяц> ...]
     */
    public function getDateParts(): array;
}