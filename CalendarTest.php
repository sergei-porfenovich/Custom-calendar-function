<?php
declare(strict_types=1);
require_once('Calendar.php');
require_once('Calendar_days.php');

use PHPUnit\Framework\TestCase;

class CalculatorTests extends TestCase
{
     /** @var Calendar */
    private $calendar;

    protected function setUp(): void
    {
        $this->calendar = new Calendar();
    }

    protected function tearDown(): void
    {
        $this->calendar = NULL;
    }

    public function Provider_test_week_day()
    {
        return new Calendar_days();
    }

    public function Provider_test_day_Ex()
    {
        $even_month_days = Calendar::EVEN_MONTH_DAYS;
        return [
            [[22, 13, 1990], "In leap year last month has $even_month_days days"],
            [[22, 02, 1990], "Even month has $even_month_days days"],
            [[01, 01, -1], "Invalid 'years' parameter"],
            [[01, -1, 1990], "Invalid 'months' parameter"],
            [[01, 15, 1990], "Invalid 'months' parameter"],
            [[25, 01, 1990], "Invalid 'days' parameter"],
            [[-25, 01, 1990], "Invalid 'days' parameter"],
        ];
    }

    /**
     * @dataProvider Provider_test_week_day
     *
     * @param $date array
     * @param $expected_week string
     */
    public function test_week_day($date, $expected_week)
    {
        $this->calendar->set_date(...$date);
        $this->assertEquals($expected_week, $this->calendar->get_week_day());
    }

    public function additionProvider()
    {
        return new Calendar_days();
    }

    /**
     * @dataProvider Provider_test_day_Ex
     *
     * @param $date array
     * @param $expected_ex string
     */
    public function test_day_Ex($date, $expected_ex){
        $this->expectExceptionMessage($expected_ex);
        $this->calendar->set_date(...$date);
    }
}
