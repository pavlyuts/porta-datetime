<?php

/*
 * Library to handle Portone billing datetime and date strings as objects
 */

namespace PortaDateTimeTest;

/**
 * Test class for PortaDateTime
 */
class PortaDateTimeTest extends \PHPUnit\Framework\TestCase {

    const ZONE = 'Pacific/Palau';
    const DATETIME = '2023-03-20 07:38:17';
    const DATE = '2023-03-20';
    const LOCAL_DATETIME = '2023-03-20 16:38:17';
    const FIRST_MOMENT = '2023-03-19 15:00:00';
    const LAST_MOMENT = '2023-03-20 14:59:59';
    const NEXT_FIRST = '2023-03-20 15:00:00';

    public function testCreate() {
        $t1 = new \PortaDateTime(self::LOCAL_DATETIME, self::ZONE);
        $t2 = new \PortaDateTime(self::LOCAL_DATETIME, new \DateTimeZone(self::ZONE));
        $this->assertEquals(static::DATETIME, $t1->formatPorta());
        $this->assertEquals(static::DATETIME, $t2->formatPorta());
    }

    public function testCreateException() {
        $this->expectException(\ErrorException::class);
        $t1 = new \PortaDateTime(self::LOCAL_DATETIME, []);
    }

    public function testCrateFromPorta() {
        $t = \PortaDateTime::createFromPortaString(self::DATETIME, self::ZONE);
        $this->assertEquals(self::LOCAL_DATETIME, $t->format(\PortaDateTime::PORTA_DATETIME));
    }

    public function testMoments() {
        $t = new \PortaDateTime(self::LOCAL_DATETIME, self::ZONE);
        $this->assertEquals(self::FIRST_MOMENT, $t->getFirstMomentString());
        $this->assertEquals(self::LAST_MOMENT, $t->getLastMomentString());
        $this->assertEquals(self::NEXT_FIRST, $t->getFirstMomentNextDayString());
    }

    public function testCreateFromPortaString() {
        $this->assertEquals(self::DATETIME,
                \PortaDateTime::createFromPortaString(self::DATETIME)
                        ->format(\PortaDateTime::PORTA_DATETIME));
        $this->assertEquals(self::LOCAL_DATETIME,
                \PortaDateTime::createFromPortaString(self::DATETIME, self::ZONE)
                        ->format(\PortaDateTime::PORTA_DATETIME));
    }
    
    public function testCreateFromDateString() {
        $this->assertEquals(self::FIRST_MOMENT,
                \PortaDateTime::createFromPortaDateString(self::DATE, self::ZONE)
                ->formatPorta());
    }

    public function testCreateAndFormat() {
        $t = new \DateTime(self::LOCAL_DATETIME, new \DateTimeZone(self::ZONE));
        $pt = \PortaDateTime::createPortaFromInterface($t);
        $this->assertEquals(self::DATETIME, $pt->formatPorta());
        $this->assertEquals(self::DATETIME, \PortaDateTime::formatDateTime($t));
    }
    
}
