<?php

/**
 * Created by PhpStorm.
 * User: serj0987
 * Date: 22.10.17
 * Time: 11:30
 */

require_once ('ComparableDate.php');

/**
 * Class CustomDate
 * Класс для работы с датой формата H:i:s d.m.Y
 * Допускает задание даты в частичном формате и сравнение частично заданных дат
 */
class CustomDate implements ComparableDate
{
    protected $rawInput;
    protected $parts = [];

    protected $partsDelimiter = ' ';
    protected $dateDelimiter = '.';
    protected $timeDelimiter = ':';

    /**
     * CustomDate constructor.
     * @param string $date - строка с датой которую необходимо разобрать
     */
    public function __construct(string $date)
    {
        $this->rawInput = $date;
        $this->parts = array_fill(0, 6, null);
        $basicParts = $this->getBasicParts($this->rawInput);
        $this->parseDate($basicParts['date']);
        $this->parseTime($basicParts['time']);
    }

    /**
     * Разделить строку с датой, на дату отдельно и время отдельно
     * @param string $input - дата и время в требуемом формате
     * @return array - ['date' => '21.07.2017', 'time' => '01:00:05']
     * @throws Exception - Если помимо даты и времени разделяемой пробелами обнаружились и другие элементы
     */
    protected function getBasicParts(string $input): array
    {
        $parts = explode($this->partsDelimiter, $input);
        $result = ['date' => null, 'time' => null];

        if (count($parts) > 2) {
            throw new \Exception('Invalid date format');
        }

        foreach ($parts as $part) {
            if (strpos($part, $this->timeDelimiter) !== false) {
                $result['time'] = $part;
            } else {
               $result['date'] = $part;
            }
        }

        return $result;
    }

    /**
     * Выполнить парсинг даты и записать в отдельные элементы массива - год, месяц, день
     * @param $rawDate - дата в строковом формате
     */
    protected function parseDate($rawDate): void
    {
        if (empty($rawDate)) {
            return;
        }

        $parts = array_reverse(explode($this->dateDelimiter, $rawDate));

        if (strlen($parts[0]) == 4) {
            $this->parts[static::YEAR_KEY] = $this->preparePart(array_shift($parts));
        }

        $resultKey = static::MONTH_KEY;

        foreach ($parts as $part) {
            if (strlen($part) == 2) {
                    $this->parts[$resultKey] = $this->preparePart($part);
                    $resultKey++;
            }
        }
    }

    /**
     * Выполнить парсинг времени и записать в отдельные элементы массива - часы, минуты, секунды
     * @param $rawTime - время в строковом формате
     */
    protected function parseTime($rawTime): void
    {
        if (empty($rawTime)) {
            return;
        }

        $parts = explode($this->timeDelimiter, $rawTime);
        $resultKey = static::HOUR_KEY;

        foreach ($parts as $part) {
            if (strlen($part) == 2) {
                $this->parts[$resultKey] = $this->preparePart($part);
                $resultKey++;
            }
        }
    }

    /**
     * Сравнить текущую дату с указанной
     * @param ComparableDate $date - дата с которой будет сравниваться текущая дата
     * @return int - 1 если текущая дата больше, 0 если равны, -1 если меньше
     */
    public function compare(ComparableDate $date): int
    {
        $dateParts = $date->getDateParts();
        for ($i = 0; $i <= 5; $i++) {
            if (!is_null($dateParts[$i]) && !is_null($this->parts[$i])) {
                if ($this->parts[$i] > $dateParts[$i]) {
                    return 1;
                }

                if ($this->parts[$i] < $dateParts[$i]) {
                    return -1;
                }
            }
        }

        return 0;
    }

    /**
     * Получить разобранную дату ввиде массива
     *
     * @return array - [static::YEAR_KEY => <год>, static::MONTH_KEY => <месяц> ...]
     */
    public function getDateParts(): array
    {
        return $this->parts;
    }

    /**
     * Привести значение к целому числу если оно содержит цифры, иначе вернуть null
     * @param string $part - значение передаваемое ввиде строки
     * @return int|null
     */
    protected function preparePart(string $part)
    {
        if (is_numeric($part)) {
            return (int) $part;
        }

        return null;
    }
}