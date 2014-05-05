<?php

class Date extends DateTime {
	
	const SUNDAY    = 0;
	const MONDAY    = 1;
	const TUESDAY   = 2;
	const WEDNESDAY = 3;
	const THURSDAY  = 4;
	const FRIDAY    = 5;
	const SATURDAY  = 6;
	
	protected static $DAYS = array(
		self::SUNDAY    => 'Sunday',
		self::MONDAY    => 'Monday',
		self::TUESDAY   => 'Tuesday',
		self::WEDNESDAY => 'Wednesday',
		self::THURSDAY  => 'Thursday',
		self::FRIDAY    => 'Friday',
		self::SATURDAY  => 'Saturday'
	);
	
	const FIRST_DAY_OF_WEEK = self::MONDAY;
	const LAST_DAY_OF_WEEK = self::SATURDAY;
	
	const DEFAULT_FORMAT = 'Y-m-d H:i:s';
	
	/**
	 * @var string
	 */
	private $defaultFormat = self::DEFAULT_FORMAT;
	
	
//== construction ==============================================================
	
	public function __construct($time = NULL, $timeZone = NULL) {
		if ($timeZone !== NULL) {
			parent::__construct($time, self::ensureTimezone($timeZone));
		} else {
			parent::__construct($time, NULL);
		}
	}
	
	protected static function ensureTimezone($object) {
		if ($object instanceof DateTimeZone) {
			return $object;
		}
		$tz = new DateTimeZone($object);
		if ($tz === false) {
			throw new InvalidArgumentException('Unknown or bad timezone ('.$object.')');
		}
		return $tz;
   }
	
	public static function create($year = NULL, $month = NULL, $day = NULL, $hour = null, $minute = null, $second = null, $tz = null) {
		$year  = ($year  === NULL) ? date('Y') : $year;
		$month = ($month === NULL) ? date('n') : $month;
		$day   = ($day   === NULL) ? date('j') : $day;
		
		if ($hour === null) {
			$hour = date('G');
			$minute = ($minute === null) ? date('i') : $minute;
			$second = ($second === null) ? date('s') : $second;
		} else {
			$minute = ($minute === null) ? 0 : $minute;
			$second = ($second === null) ? 0 : $second;
		}
		
		return self::createFromFormat('Y-n-j G:i:s', sprintf('%s-%s-%s %s:%02s:%02s', $year, $month, $day, $hour, $minute, $second), $tz);
	}
	
	/**
	 * @param string $format
	 * @param string $time
	 * @param DateTimeZone|null $timeZone
	 * @return Date
	 * @throws InvalidArgumentException
	 */
	public static function createFromFormat($format, $time, $timeZone = NULL) {
		if ($timeZone !== null) {
			$dt = parent::createFromFormat($format, $time, self::ensureTimezone($timeZone));
		} else {
			$dt = parent::createFromFormat($format, $time);
		}

		if ($dt instanceof DateTime) {
			return self::instance($dt);
		}

		$errors = static::getLastErrors();
		throw new InvalidArgumentException(implode(PHP_EOL, $errors['errors']));
	}
	
	/**
	 * @param DateTime $dateTime
	 * @return Date
	 */
	public static function instance(DateTime $dateTime) {
		return new static($dateTime->format('Y-m-d H:i:s'), $dateTime->getTimeZone());
	}
	
	/**
	 * @param DateTimeZone|null $timeZone
	 * @return Date
	 */
	public static function now($timeZone = NULL) {
		return new static(null, $timeZone);
	}
	
	/**
	 * 
	 * @param integer $timestamp
	 * @param DateTimeZone|null $timeZone
	 * @return Date
	 */
	public static function createFromTimestamp($timestamp, $timeZone = NULL) {
		return self::now($timeZone)->setTimestamp($timestamp);
	}
	
	/**
	 * 
	 * @return Date
	 */
	public function copy() {
		$copy = self::instance($this);
		$copy->defaultFormat = $this->defaultFormat;
		return $copy;
	}
	
	
//== setters and getters =======================================================
	
	/** @return integer */
	public function getYear()         { return intval($this->format('Y')); }
	/** @return integer */
	public function getMonth()        { return intval($this->format('n')); }
	/** @return integer */
	public function getDay()          { return intval($this->format('j')); }
	/** @return integer */
	public function getHour()         { return intval($this->format('G')); }
	/** @return integer */
	public function getMinute()       { return intval($this->format('i')); }
	/** @return integer */
	public function getSecond()       { return intval($this->format('s')); }
	/** @return integer */
	public function getDayOfWeek()    { return intval($this->format('w')); }
	/** @return integer */
	public function getDayOfYear()    { return intval($this->format('z')); }
	/** @return integer */
	public function getWeekOfYear()   { return intval($this->format('W')); }
	/** @return integer */
	public function getDaysInMonth()  { return intval($this->format('t')); }
	/** @return integer */
	public function getTimestamp()    { return intval($this->format('U')); }
	/** @return integer */
	public function getQuarter()      { return intval(($this->getMonth() - 1) / 3) + 1; }
	/** @return DateTimeZone */
	public function getTimezone()     { return parent::getTimezone(); }
	/** @return string */
	public function getTimezoneName() { return parent::getTimezone()->getName(); }
	
	/**
	 * @param integer $year
	 * @return Date
	 */
	public function setYear($year)     { parent::setDate($year, $this->getMonth(), $this->getDay()); return $this; }

	/**
	 * @param integer $month
	 * @return \Date
	 */
	public function setMonth($month)   { parent::setDate($this->getYear(), $month, $this->getDay()); return $this; }
	public function setDay($day)       { parent::setDate($this->getYear(), $this->getMonth(), $day); return $this; }
	public function setHour($hour)     { parent::setTime($hour, $this->getMinute(), $this->getSecond()); return $this; }
	public function setMinute($minute) { parent::setTime($this->getHour(), $minute, $this->getSecond()); return $this; }
	public function setSecond($second) { parent::setTime($this->getHour(), $this->getMinute(), $second); return $this; }
	public function setTimestamp($unixtimestamp) { parent::setTimestamp($unixtimestamp); return $this; }
	public function setTimezone($timezone) { parent::setTimezone(self::ensureTimezone($timezone)); return $this; }
	
	public function isWeekday() { 
		return $this->getDayOfWeek() != self::SATURDAY && $this->getDayOfWeek() != self::SUNDAY;
	}
	
	public function isWeekend() {
		return !$this->isWeekday();
	}
	
	public function isSameDay(Date $date) {
		return $this->toDateString() == $date->toDateString();
	}

	
//== formating =================================================================
	
	public function setUserFormat($format) {
		$this->defaultFormat = $format;
	}
	
	public function getUserFormat() {
		return $this->defaultFormat;
	}
	
	public function formatLocalized($format) {
		// Check for Windows to find and replace the %e
		// modifier correctly
		if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
			$format = preg_replace('#(?<!%)((?:%%)*)%e#', '\1%#d', $format);
		}
		return strftime($format, $this->getTimestamp());
	}
	
	public function __toString() {
		return $this->format($this->defaultFormat);
	}
	
	/**
	 * 
	 * @return string
	 */
	public function toDateString() {
		return $this->format('Y-m-d');
	}
	
	/**
	 * @param integer $years
	 * @return Date
	 */
	public function addYears($years) { 
		return $this->modify(intval($years).' year');
	}
	
	/**
	 * @param integer $months
	 * @return Date
	 */
	public function addMonths($months) {
		return $this->modify(intval($months).' month');
	}
	
	/**
	 * @param integer $days
	 * @return Date
	 */
	public function addDays($days) {
		return $this->modify(intval($days).' day');
	}
	
	/**
	 * @param integer $weekdays
	 * @return Date
	 */
	public function addWeekdays($weekdays) {
		return $this->modify(intval($weekdays).' weekday');
	}
	
	/**
	 * @param integer $weeks
	 * @return Date
	 */
	public function addWeeks($weeks) {
		return $this->modify(intval($weeks). ' week');
	}
	
	public function setToStartOfDay() {
		return $this->setHour(0)->setMinute(0)->setSecond(0);
	}
	
	public function setToEndOfDay() {
		return $this->setHour(23)->setMinute(59)->setSecond(59);
	}
	
	public function setToStartOfMonth() {
		return $this->setDay(1)->setToStartOfDay();
	}
	
	public function setToEndOfMonth() {
		return $this->setDay($this->getDaysInMonth())->setToEndOfDay();
	}
	
	public function setToStartOfYear() {
		return $this->setMonth(1)->setToStartOfMonth();
	}
	
	public function setToEndOfYear() {
		return $this->setMonth(12)->setToEndOfMonth();
	}
	
	public function setToNextWeekday($dayOfWeek = NULL, $keepTime = FALSE) {
		if ($dayOfWeek === NULL) {
			$dayOfWeek = $this->getDayOfWeek();
		}
		$this->modify('next '.self::$DAYS[$dayOfWeek]);
		return $keepTime ? $this : $this->setToStartOfDay();
	}
	
	public function setToPreviousWeekday($dayOfWeek = NULL, $keepTime = FALSE) {
		if ($dayOfWeek === NULL) {
			$dayOfWeek = $this->getDayOfWeek();
		}
		$this->modify('last '.self::$DAYS[$dayOfWeek]);
		return $keepTime ? $this : $this->setToStartOfDay();
	}
	
	public function setToStartOfWeek($keepTime = FALSE) {
		if ($this->getDayOfWeek() != self::FIRST_DAY_OF_WEEK) {
			$this->setToPreviousWeekday(self::FIRST_DAY_OF_WEEK, TRUE);
		}
		return $keepTime ? $this : $this->setToStartOfDay();
	}
	
	public function setToEndOfWeek($keepTime = FALSE) {
		if ($this->getDayOfWeek() != self::LAST_DAY_OF_WEEK) {
			$this->setToPreviousWeekday(self::LAST_DAY_OF_WEEK, TRUE);
		}
		return $keepTime ? $this : $this->setToEndOfDay();
	}
	
	/**
	 * 
	 * @param integer $dayOfWeek
	 * @param bool $keepTime
	 * @return Date
	 */
	public function setToFirstWeekdayOfMonth($dayOfWeek = NULL, $keepTime = FALSE) {
		if ($keepTime) {
			$hour   = $this->getHour();
			$minute = $this->getMinute();
			$second = $this->getSecond();
		}
		if ($dayOfWeek === NULL) {
			$this->setDay(1);
		} else {
			$this->modify('first '.self::$DAYS[$dayOfWeek].' of '.$this->format('F').' '.$this->getYear());
		}
		return $keepTime ? $this->setTime($hour, $minute, $second) : $this->setToStartOfDay();
	}
	
	public function setToLastWeekdayOfMonth($dayOfWeek = NULL, $keepTime = FALSE) {
		if ($keepTime) {
			$hour   = $this->getHour();
			$minute = $this->getMinute();
			$second = $this->getSecond();
		}
		if ($dayOfWeek === NULL) {
			$this->setDay($this->getDaysInMonth());
		} else {
			$this->modify('last '.self::$DAYS[$dayOfWeek].' of '.$this->format('F').' '.$this->getYear());
		}
		return $keepTime ? $this->setTime($hour, $minute, $second) : $this->setToStartOfDay();
	}
		
}
