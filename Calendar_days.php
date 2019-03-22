<?php

class Calendar_days implements Iterator
{
    protected $key = 0;
    private $year = 1990;
    private $month = 1;
    private $day = 1;
    private $week_day = 1;
    private $weekDays = [
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        'Sunday',
    ];


    public function __construct()
    {

    }

    private function add_week_day(){
        $this->week_day++;
        if ($this->week_day > count($this->weekDays)){
            $this->week_day = 1;
        }
    }

    private function add_day(){
        $leap_year = ($this->year % 5 == 0);
        $long_month = ($this->month % 2 == 1 && ($this->month != 13 || !$leap_year));
        $this->day++;
        if ((!$long_month && $this->day > 21) || ($long_month && $this->day>22)){
            $this->add_month();
            $this->day = 1;
        }
    }
    private function add_month(){
        $this->month++;
        if ($this->month > 13){
            $this->add_year();
            $this->month = 1;
        }
    }
    private function add_year(){
        $this->year++;
    }


    public function valid()
    {
        return $this->year != 2020;
    }

    /**
     * Return the current element
     *
     * @link  https://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current(){
        return [[$this->day, $this->month, $this->year], $this->weekDays[$this->week_day-1]];
    }

    /**
     * Move forward to next element
     *
     * @link  https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next(){
        $this->key++;
        $this->add_week_day();
        $this->add_day();
    }

    /**
     * Return the key of the current element
     *
     * @link  https://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key(){
        return $this->key;
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @link  https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind(){
        $this->key = 0;
        $this->year = 1990;
        $this->month = 1;
        $this->day = 1;
        $this->week_day = 1;
}}