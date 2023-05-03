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
     * Prepares 'last moment' like 23:59:59 at the defined timezone and then format 
     * it as Portabilling datetime string in UTC timezone
     * 
     * @return string
     */
    public function getLastMomentString(): string {
        return $this->getMoment(23, 59, 59)->formatPorta();
    }

    /**
     * Prepares 'first moment' like 00:00:00 at the defined timezone and then format 
     * it as Portabilling datetime string in UTC timezone
     * 
     * @return string
     */
    public function getFirstMomentString(): string {
        return $this->getMoment(0, 0, 0)->formatPorta();
    }

    /**
     * Prepares 'first moment' like 00:00:00 of the next day at the defined timezone 
     * and then format it as Portabilling datetime string in UTC timezone
     * 
     * @return string
     */
    public function getFirstMomentNextDayString(): string {
        return $this->getMoment(0, 0, 0)->modify('+1 day')->formatPorta();
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
     * @param string $datetime - the date string as you got it form billing
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
     * Returns datetime string in UTC format for DateTime object
     * @param DateTime $datetime
     * @return string
     */
    public static function formatDateTime(DateTime $datetime): string {
        return $datetime
                        ->setTimezone(new DateTimeZone('UTC'))
                        ->format(self::PORTA_DATETIME);
    }

    protected function getMoment($h, $m, $s): PortaDateTime {
        return (clone $this)->setTime($h, $m, $s);
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
