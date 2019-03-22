<?php
/**
 * test calendar calculations
 * @author Sergei Porfenovich <sergei.porfenovich@gmail.com>
 */

// So... what day of the week at 17.11.2013 in our Calendar?
require_once('Calendar.php');

$date_for_test = [17, 11, 2013];
$calendar = new Calendar(...$date_for_test);
$calendar->set_date(0, 5, 0);
echo $calendar->get_date_and_week_day();