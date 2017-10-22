<?php
use PHPUnit\Framework\TestCase;

/**
 * Created by PhpStorm.
 * User: serj0987
 * Date: 22.10.17
 * Time: 14:32
 */

/**
 * @covers CustomDate
 */
final class CustomDateTest extends TestCase
{
    public function testParseFullDateTime(): void
    {
       $customDate = new CustomDate('01:00:05 21.07.2017');
       $parts = $customDate->getDateParts();
       $this->assertArrayHasKey(CustomDate::YEAR_KEY, $parts);
       $this->assertEquals(2017, $parts[CustomDate::YEAR_KEY]);
       $this->assertArrayHasKey(CustomDate::MONTH_KEY, $parts);
       $this->assertEquals(7, $parts[CustomDate::MONTH_KEY]);
       $this->assertArrayHasKey(CustomDate::DAY_KEY, $parts);
       $this->assertEquals(21, $parts[CustomDate::DAY_KEY]);

       $this->assertArrayHasKey(CustomDate::HOUR_KEY, $parts);
       $this->assertEquals(1, $parts[CustomDate::HOUR_KEY]);

        $this->assertArrayHasKey(CustomDate::MINUTE_KEY, $parts);
        $this->assertEquals(0, $parts[CustomDate::MINUTE_KEY]);

        $this->assertArrayHasKey(CustomDate::SECOND_KEY, $parts);
        $this->assertEquals(5, $parts[CustomDate::SECOND_KEY]);
    }

    public function testParseDateTimeWithoutSeconds(): void
    {
        $customDate = new CustomDate('01:05 21.07.2017');
        $parts = $customDate->getDateParts();
        $this->assertEquals(2017, $parts[CustomDate::YEAR_KEY]);
        $this->assertEquals(7, $parts[CustomDate::MONTH_KEY]);
        $this->assertEquals(21, $parts[CustomDate::DAY_KEY]);

        $this->assertEquals(1, $parts[CustomDate::HOUR_KEY]);
        $this->assertEquals(5, $parts[CustomDate::MINUTE_KEY]);
        $this->assertEquals(null, $parts[CustomDate::SECOND_KEY]);
    }

    public function testParseDateTimeWithoutMinutes(): void
    {
        $customDate = new CustomDate('01: 21.07.2017');
        $parts = $customDate->getDateParts();
        $this->assertEquals(2017, $parts[CustomDate::YEAR_KEY]);
        $this->assertEquals(7, $parts[CustomDate::MONTH_KEY]);
        $this->assertEquals(21, $parts[CustomDate::DAY_KEY]);

        $this->assertEquals(1, $parts[CustomDate::HOUR_KEY]);
        $this->assertEquals(null, $parts[CustomDate::MINUTE_KEY]);
        $this->assertEquals(null, $parts[CustomDate::SECOND_KEY]);
    }

    public function testParseOnlyDate(): void
    {
        $customDate = new CustomDate('21.07.2017');
        $parts = $customDate->getDateParts();
        $this->assertEquals(2017, $parts[CustomDate::YEAR_KEY]);
        $this->assertEquals(7, $parts[CustomDate::MONTH_KEY]);
        $this->assertEquals(21, $parts[CustomDate::DAY_KEY]);

        $this->assertEquals(null, $parts[CustomDate::HOUR_KEY]);
        $this->assertEquals(null, $parts[CustomDate::MINUTE_KEY]);
        $this->assertEquals(null, $parts[CustomDate::SECOND_KEY]);
    }

    public function testParseOnlyDateWithoutDay(): void
    {
        $customDate = new CustomDate('07.2017');
        $parts = $customDate->getDateParts();
        $this->assertEquals(2017, $parts[CustomDate::YEAR_KEY]);
        $this->assertEquals(7, $parts[CustomDate::MONTH_KEY]);
        $this->assertEquals(null, $parts[CustomDate::DAY_KEY]);

        $this->assertEquals(null, $parts[CustomDate::HOUR_KEY]);
        $this->assertEquals(null, $parts[CustomDate::MINUTE_KEY]);
        $this->assertEquals(null, $parts[CustomDate::SECOND_KEY]);
    }

    public function testParseOnlyYear(): void
    {
        $customDate = new CustomDate('2017');
        $parts = $customDate->getDateParts();
        $this->assertEquals(2017, $parts[CustomDate::YEAR_KEY]);
        $this->assertEquals(null, $parts[CustomDate::MONTH_KEY]);
        $this->assertEquals(null, $parts[CustomDate::DAY_KEY]);

        $this->assertEquals(null, $parts[CustomDate::HOUR_KEY]);
        $this->assertEquals(null, $parts[CustomDate::MINUTE_KEY]);
        $this->assertEquals(null, $parts[CustomDate::SECOND_KEY]);
    }

    public function testParseFullTime(): void
    {
        $customDate = new CustomDate('01:05:17');
        $parts = $customDate->getDateParts();
        $this->assertEquals(null, $parts[CustomDate::YEAR_KEY]);
        $this->assertEquals(null, $parts[CustomDate::MONTH_KEY]);
        $this->assertEquals(null, $parts[CustomDate::DAY_KEY]);

        $this->assertEquals(1, $parts[CustomDate::HOUR_KEY]);
        $this->assertEquals(5, $parts[CustomDate::MINUTE_KEY]);
        $this->assertEquals(17, $parts[CustomDate::SECOND_KEY]);
    }

    public function testParseTimeWithoutSeconds(): void
    {
        $customDate = new CustomDate('01:05');
        $parts = $customDate->getDateParts();
        $this->assertEquals(null, $parts[CustomDate::YEAR_KEY]);
        $this->assertEquals(null, $parts[CustomDate::MONTH_KEY]);
        $this->assertEquals(null, $parts[CustomDate::DAY_KEY]);

        $this->assertEquals(1, $parts[CustomDate::HOUR_KEY]);
        $this->assertEquals(5, $parts[CustomDate::MINUTE_KEY]);
        $this->assertEquals(null, $parts[CustomDate::SECOND_KEY]);
    }

    public function testParseOnlyHour(): void
    {
        $customDate = new CustomDate('01:');
        $parts = $customDate->getDateParts();
        $this->assertEquals(null, $parts[CustomDate::YEAR_KEY]);
        $this->assertEquals(null, $parts[CustomDate::MONTH_KEY]);
        $this->assertEquals(null, $parts[CustomDate::DAY_KEY]);

        $this->assertEquals(1, $parts[CustomDate::HOUR_KEY]);
        $this->assertEquals(null, $parts[CustomDate::MINUTE_KEY]);
        $this->assertEquals(null, $parts[CustomDate::SECOND_KEY]);
    }

    public function testInvalidFormat(): void
    {
        $customDate = new CustomDate('fdgdfthj tfhuk');
        $parts = $customDate->getDateParts();
        $this->assertEquals(null, $parts[CustomDate::YEAR_KEY]);
        $this->assertEquals(null, $parts[CustomDate::MONTH_KEY]);
        $this->assertEquals(null, $parts[CustomDate::DAY_KEY]);

        $this->assertEquals(null, $parts[CustomDate::HOUR_KEY]);
        $this->assertEquals(null, $parts[CustomDate::MINUTE_KEY]);
        $this->assertEquals(null, $parts[CustomDate::SECOND_KEY]);
    }

    public function testInvalidFormat2(): void
    {
        $this->expectException(\Exception::class);
        new CustomDate('fdgdfthj tfhuk fdghgjk gfhgkhjh');
    }

    public function testCompareEqualFullDates(): void
    {
        $customDate = new CustomDate('01:00:05 21.07.2017');
        $this->assertEquals(0, $customDate->compare($customDate));
    }

    public function testCompareFullDateWithLowerDate(): void
    {
        $customDate = new CustomDate('01:00:05 21.07.2017');
        $lowerDate = new CustomDate('01:00:00 21.07.2017');
        $this->assertEquals(1, $customDate->compare($lowerDate));
    }

    public function testCompareDateWithLowerPartDate(): void
    {
        $customDate = new CustomDate('01:00:05 21.07.2017');
        $lowerDate = new CustomDate('00:30 2017');
        $this->assertEquals(1, $customDate->compare($lowerDate));
    }

    public function testComparePartDateWithLowerDate(): void
    {
        $customDate = new CustomDate('01: 2017');
        $lowerDate = new CustomDate('00:30:05 21.07.2017');
        $this->assertEquals(1, $customDate->compare($lowerDate));
    }

    public function testCompareFullDateWithBiggerDate(): void
    {
        $customDate = new CustomDate('01:00:05 21.07.2017');
        $biggerDate = new CustomDate('00:30:00 21.08.2017');
        $this->assertEquals(-1, $customDate->compare($biggerDate));
    }
}