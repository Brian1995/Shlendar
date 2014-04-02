<?php

/**
 * Returns the first day of the week for the locale. If locale is obmitted or 
 * NULL the default locale will be used.
 * 
 * @param string $locale
 * @return int
 */
function time_get_first_day_of_week($locale=NULL) {
	return 1; // ignore locale for now
}

/**
 * Creates a new DateTime object with the date set to the first day of the 
 * month, relative to the date parameter.
 * 
 * @param \DateTime $dateTime the date used as reference
 * @return \DateTime new date of the first day in month
 */
function time_first_day_in_month($dateTime, $offset = 0) {
	$c = clone $dateTime;
	$c->modify('first day of '.$offset.' month');
	return $c;
}

/**
 * Creats a new DateTime object with the date set to the first day of the week, 
 * relative to the date parameter.
 * 
 * @param \DateTime $dateTime the date used as reference
 * @param integer $firstDayOfWeek the first day of the week, defaults to 1 
 *        which is monday.
 * @return \DateTime new date of the first day in week
 */
function time_first_day_of_week($dateTime, $firstDayOfWeek=NULL, $locale=NULL) {
	if (is_null($firstDayOfWeek)) {
		$firstDayOfWeek = time_get_first_day_of_week($locale);
	}
	$difference = $firstDayOfWeek - $dateTime->format('w');
	if ($difference > 0) {
		$difference -= 7;
	}
	$c = clone $dateTime;
	$c->modify("$difference days");
	return $c;
}


?>
