<?php

/*
 * Library to handle Portone billing datetime and date strings as objects
 */

/**
 * Class to handle Portone billing datetime and date strings as objects
 *
 */
class PortaDateTime extends DateTime {

    const PORTA_DATETIME = 'Y-m-d H:i:s';
    const PORTA_DATE = 'Y-m-d';

    /**
     * 
     * @param string $datetime - the same as basic DateTime parameter
     * @param string|DateTimeZone $timezone timezone string or object
     */
    public function __construct(string $datetime = "now", $timezone = "UTC") {
        parent::__construct($datetime, self::prepreTimezone($timezone));
    }

    /**
     * Return Portaone-formatted datetime string at UTC timezone
     * 
     */
    public function formatPorta(): string {
        return (clone $this)
                        ->setTimezone(new \DateTimeZone('UTC'))
                        ->format(self::PORTA_DATETIME);
    }

    /**
     * Set time to the first moment of the day in the current timezone 
     * 
     * @return PortaDateTime self object for chaining
     */
    public function setFirstMoment(): PortaDateTime {
        $this->setTime(0, 0, 0);
        return $this;
    }

    /**
     * Set time to the last moment of the day (23:59:59) in the current timezone 
     * 
     * @return PortaDateTime self object for chaining
     */
    public function setLastMoment(): PortaDateTime {
        $this->setTime(23, 59, 59);
        return $this;
    }

    /**
     * Set date-time to the first moment (00:00:00) of the next month relative to 
     * the current DateTime value and timezone.
     * 
     * @return PortaDateTime self object for chaining
     */
    public function setFirstMomentOfNextMonth(): PortaDateTime {
        $this->setFirstMoment();
        $this->modify('first day of next month');
        return $this;
    }

    /**
     * Prepares 'last moment' like 23:59:59 at the defined timezone and then format 
     * it as Portabilling datetime string in UTC timezone
     * 
     * @return string
     */
    public function getLastMomentString(): string {
        return (clone $this)->setLastMoment()->formatPorta();
    }

    /**
     * Prepares 'first moment' like 00:00:00 at the defined timezone and then format 
     * it as Portabilling datetime string in UTC timezone
     * 
     * @return string
     */
    public function getFirstMomentString(): string {
        return (clone $this)->setFirstMoment()->formatPorta();
    }

    /**
     * Prepares 'first moment' like 00:00:00 of the next day at the defined timezone 
     * and then format it as Portabilling datetime string in UTC timezone
     * 
     * @return string
     */
    public function getFirstMomentNextDayString(): string {
        return (clone $this)->modify('+1 day')->setFirstMoment()->formatPorta();
    }

    /**
     * Calculates prorated value from given date till the end of the month
     * 
     * @param float $fee - basic rate to prorate
     * @return float - prorated vlue
     */
    public function prorateTillEndOfMonth(float $fee): float {
        $days = (int) $this->format('t');
        return round($days - $this->format('j') + 1) * $fee / $days;
    }

    /**
     * Checks if the datetime on the future or not
     * 
     * @return bool true if datetime in the future
     */
    public function inFuture(): bool {
        return $this > (new DateTime());
    }

    /**
     * Checks if the datetime on the future or not
     * 
     * @return bool true if datetime in the future
     */
    public function inPast(): bool {
        return $this < (new DateTime());
    }

    /**
     * Return true if object datetime is between $from and $to
     * 
     * @param PortaDateTime|null $from
     * @param PortaDateTime|null $to
     * @return bool
     */
    public function between(?DateTimeInterface $from, ?DateTimeInterface $to): bool {
        return (is_null($from) || ($this >= $from)) && (is_null($to) || ($this <= $to));
    }

    /**
     * Creates object from Portaone datetime string and shift the timezone to desired.
     * Please, mind the billing always returns datetime in UTC.
     * 
     * @param string $datetime - the datetime string as you got it form billing
     * @param type $timezone - target timezone string or object
     * @return PortaDateTime
     */
    public static function createFromPortaString(string $datetime, $timezone = 'UTC'): PortaDateTime {
        return (new PortaDateTime($datetime, 'UTC'))
                        ->setTimezone(self::prepreTimezone($timezone));
    }

    /**
     * Creates object from Portaone date-only string and the time is set to zero 
     * in desired timezone.
     * 
     * @param string $date - the date string as you got it form billing
     * @param type $timezone - target timezone string or object
     * @return PortaDateTime
     */
    public static function createFromPortaDateString(string $date, $timezone = 'UTC'): PortaDateTime {
        return (new PortaDateTime($date, self::prepreTimezone($timezone)));
    }

    /**
     * Create PortaDateTime object from regular DateTimeInterface object
     * 
     * @param \DateTimeInterface $object
     * @return \DateTime
     */
    public static function createPortaFromInterface(\DateTimeInterface $object): PortaDateTime {
        return (new PortaDateTime('now', $object->getTimezone()))
                        ->setTimestamp($object->getTimestamp());
    }

    /**
     * Returns Portaone-format datetime string in UTC for DateTimeInterface
     * 
     * @param DateTimeInterface $datetime
     * @return string
     */
    public static function formatDateTime(DateTimeInterface $datetime): string {
        return self::createPortaFromInterface($datetime)
                        ->setTimezone(new DateTimeZone('UTC'))
                        ->format(self::PORTA_DATETIME);
    }

    protected static function prepreTimezone($timezone = 'UTC'): DateTimeZone {
        if (is_string($timezone)) {
            return new \DateTimeZone($timezone);
        } elseif ($timezone instanceof \DateTimeZone) {
            return $timezone;
        } else {
            throw new InvalidArgumentException("Timezone must be a string or a DateTimeZone object");
        }
    }

}
