<?php
/**
 * Make calendar calculations
 * @author Sergei Porfenovich <sergei.porfenovich@gmail.com>
 */
class Calendar
{
    // Task condition:
    const MONTHS_PERIOD = 2;
    const EVEN_MONTH_DAYS = 21;
    const ODD_MONTH_DAYS = 22;
    const YEAR_MONTHS = 13;
    const LEAP_YEAR_DIFF = -1;
    const LEAP_YEAR_PERIOD = 5;
    // From this condition we get:
    private $years_period_days;
    private $year_days;
    private $months2_days;
    private $month_days;
    public $year = 1;
    public $month = 1;
    public $day = 1;
    /*
     * 01/01/1990 - monday, in this year 279 days (39*7 + 6) =>
     * 01/01/1991 - 'sunday' => calculate fist day:
     * 01/01/0001 - 'sunday' => $weekDays[1] should be 'sunday'
     */
    private $weekDays = [
        'Saturday',
        'Sunday',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
    ];

    public function __construct(int $days=0, int $months=0, int $years=0) {
        $this->month_days = self::ODD_MONTH_DAYS; // ODD_MONTH_DAYS, because first month is odd.
        $this->months2_days = self::ODD_MONTH_DAYS + self::EVEN_MONTH_DAYS;
        $odd_months = ceil(self::YEAR_MONTHS/self::MONTHS_PERIOD);
        $even_months = self::YEAR_MONTHS - $odd_months;
        $this->year_days = $odd_months * self::ODD_MONTH_DAYS + $even_months * self::EVEN_MONTH_DAYS;
        $this->years_period_days = self::LEAP_YEAR_PERIOD * $this->year_days + self::LEAP_YEAR_DIFF;
        $this->set_date($days, $months, $years);
    }

    /**
     * Set days/months/years past from 01/01/01
     *
     * @param $days     integer date
     * @param $months   integer month
     * @param $years    integer year
     */
    public function set_date(int $days=0, int $months=0, int $years=0){
        $new_date = [$days ? $days:$this->day, $months ? $months:$this->month, $years ? $years:$this->year];
        $this->check_exceptions(...$new_date);
        list($this->day, $this->month, $this->year) = $new_date;
    }
    /**
     * Get days/months/years
     *
     * @return array
     */
    public function get_date(){
        return [$this->day, $this->month, $this->year];
    }

    /**
     * Get format date
     *
     * @param string $symb
     *
     * @return string
     */
    public function get_fdate($symb='/'){
        return join($symb, [$this->day, $this->month, $this->year]);
    }

    /**
     * Get days/months/years past from 01/01/01
     *
     * @param $days     integer date
     * @param $months   integer month
     * @param $years    integer year
     *
     * @return array
     */
    private function get_delta(int $days, int $months, int $years){
        return [$days - 1, $months - 1, $years - 1];
    }

    /**
     * Get day count started from 01/01/01
     *
     * @return int
     */
    public function get_days_count(){
        list($d_days, $d_months, $d_years) = $this->get_delta($this->day, $this->month, $this->year);
        $count_years_5 = floor($d_years/self::LEAP_YEAR_PERIOD);
        $count_years = $d_years % self::LEAP_YEAR_PERIOD;
        $count_month_2 = floor($d_months/self::MONTHS_PERIOD);
        $count_month = $d_months % self::MONTHS_PERIOD;

        $all_days = $d_days + $count_month*$this->month_days + $count_month_2*$this->months2_days +
            $count_years*$this->year_days + $count_years_5*$this->years_period_days;
        return $all_days;
    }


    /**
     * Check for Exceptions
     *
     * @param $days     integer date
     * @param $months   integer month
     * @param $years    integer year
     *
     * @throws Exception
     */
    private function check_exceptions(int $days, int $months, int $years){
        $even_month_days = self::EVEN_MONTH_DAYS;
        $leap_year = ($years % self::LEAP_YEAR_PERIOD == 0);
        $long_month = ($months % self::MONTHS_PERIOD == 1 && ($months != self::YEAR_MONTHS || !$leap_year));
        if ($leap_year && $months == self::YEAR_MONTHS && $days == self::ODD_MONTH_DAYS){
            throw new Exception("In leap year last month has $even_month_days days");
        } elseif (!$long_month && $days == self::ODD_MONTH_DAYS) {
            throw new Exception("Even month has $even_month_days days");
        } elseif ($years < 1) {
            throw new Exception("Invalid 'years' parameter");
        } elseif ($months < 1 || $months > self::YEAR_MONTHS) {
            throw new Exception("Invalid 'months' parameter");
        } elseif ($days < 1 || $days > self::ODD_MONTH_DAYS) {
            throw new Exception("Invalid 'days' parameter");
        }
    }

    /**
     * Get a name of the week day
     *
     * @return string
     */
  public function get_week_day() {

      $all_days = $this->get_days_count();
      $week_day = $all_days % 7;
      $week_day_s = $this->weekDays[$week_day];

      return $week_day_s;
  }

  /**
     * Get a date and a name of the week day - it's for test and demonstration
     *
     * @return string
     */
  public function get_date_and_week_day() {
      $date_s = $this->get_fdate();
      $week_day_s = $this->get_week_day();

      return "<p>Date: $date_s <br> Day of the week: $week_day_s</p>";
  }
}
