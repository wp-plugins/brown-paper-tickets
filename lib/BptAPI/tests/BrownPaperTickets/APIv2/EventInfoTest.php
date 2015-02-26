<?php

namespace BrownPaperTickets\APIv2;

//use BrownPaperTickets\APIv2\eventInfo;
use PHPUnit_Framework_TestCase;

class BrownPaperTicketsGetEventInfoTest extends \PHPUnit_Framework_TestCase
{
    public function __construct()
    {
        $this->eventInfo = new EventInfo(getenv('DEVID'));
    }

    public function testGetEvents()
    {
        $events = $this->eventInfo->getEvents('chandler', null, true, true);

        $this->assertCount(5, $events);

        foreach ($events as $event) {
            // Test that we get the proper fields back
            $this->assertArrayHasKey('id', $event);
            $this->assertArrayHasKey('title', $event);
            $this->assertArrayHasKey('live', $event);
            $this->assertArrayHasKey('address1', $event);
            $this->assertArrayHasKey('address2', $event);
            $this->assertArrayHasKey('city', $event);
            $this->assertArrayHasKey('state', $event);
            $this->assertArrayHasKey('zip', $event);
            $this->assertArrayHasKey('shortDescription', $event);
            $this->assertArrayHasKey('fullDescription', $event);
            $this->assertArrayHasKey('phone', $event);
            $this->assertArrayHasKey('web', $event);
            $this->assertArrayHasKey('contactName', $event);
            $this->assertArrayHasKey('contactPhone', $event);
            $this->assertArrayHasKey('contactAddress1', $event);
            $this->assertArrayHasKey('contactAddress2', $event);
            $this->assertArrayHasKey('contactCity', $event);
            $this->assertArrayHasKey('contactState', $event);
            $this->assertArrayHasKey('contactZip', $event);
            $this->assertArrayHasKey('contactCountry', $event);
            $this->assertArrayHasKey('contactEmail', $event);

            // Test that they return the proper types
            $this->assertInternalType('integer', $event['id']);
            $this->assertInternalType('string', $event['title']);
            $this->assertInternalType('boolean', $event['live']);
            $this->assertInternalType('string', $event['address1']);
            $this->assertInternalType('string', $event['address2']);
            $this->assertInternalType('string', $event['city']);
            $this->assertInternalType('string', $event['state']);
            $this->assertInternalType('string', $event['zip']);
            $this->assertInternalType('string', $event['shortDescription']);
            $this->assertInternalType('string', $event['fullDescription']);
            $this->assertInternalType('string', $event['phone']);
            $this->assertInternalType('string', $event['web']);
            $this->assertInternalType('string', $event['contactName']);
            $this->assertInternalType('string', $event['contactPhone']);
            $this->assertInternalType('string', $event['contactAddress1']);
            $this->assertInternalType('string', $event['contactAddress2']);
            $this->assertInternalType('string', $event['contactCity']);
            $this->assertInternalType('string', $event['contactState']);
            $this->assertInternalType('string', $event['contactZip']);
            $this->assertInternalType('string', $event['contactCountry']);
            $this->assertInternalType('string', $event['contactEmail']);

        }

    }

    public function testGetSingleEvent()
    {
        $event = $this->eventInfo->getEvents('chandler', 153529, true, true);

        // Test that we only receive one event.
        $this->assertCount(1, $event);

        // Test that we get the proper fields back
        $this->assertArrayHasKey('id', $event[0]);
        $this->assertArrayHasKey('title', $event[0]);
        $this->assertArrayHasKey('live', $event[0]);
        $this->assertArrayHasKey('address1', $event[0]);
        $this->assertArrayHasKey('address2', $event[0]);
        $this->assertArrayHasKey('city', $event[0]);
        $this->assertArrayHasKey('state', $event[0]);
        $this->assertArrayHasKey('zip', $event[0]);
        $this->assertArrayHasKey('shortDescription', $event[0]);
        $this->assertArrayHasKey('fullDescription', $event[0]);
        $this->assertArrayHasKey('phone', $event[0]);
        $this->assertArrayHasKey('web', $event[0]);
        $this->assertArrayHasKey('contactName', $event[0]);
        $this->assertArrayHasKey('contactPhone', $event[0]);
        $this->assertArrayHasKey('contactAddress1', $event[0]);
        $this->assertArrayHasKey('contactAddress2', $event[0]);
        $this->assertArrayHasKey('contactCity', $event[0]);
        $this->assertArrayHasKey('contactState', $event[0]);
        $this->assertArrayHasKey('contactZip', $event[0]);
        $this->assertArrayHasKey('contactCountry', $event[0]);
        $this->assertArrayHasKey('contactEmail', $event[0]);

        // Test that they return the proper types
        $this->assertInternalType('integer', $event[0]['id']);
        $this->assertInternalType('string', $event[0]['title']);
        $this->assertInternalType('boolean', $event[0]['live']);
        $this->assertInternalType('string', $event[0]['address1']);
        $this->assertInternalType('string', $event[0]['address2']);
        $this->assertInternalType('string', $event[0]['city']);
        $this->assertInternalType('string', $event[0]['state']);
        $this->assertInternalType('string', $event[0]['zip']);
        $this->assertInternalType('string', $event[0]['shortDescription']);
        $this->assertInternalType('string', $event[0]['fullDescription']);
        $this->assertInternalType('string', $event[0]['phone']);
        $this->assertInternalType('string', $event[0]['web']);
        $this->assertInternalType('string', $event[0]['contactName']);
        $this->assertInternalType('string', $event[0]['contactPhone']);
        $this->assertInternalType('string', $event[0]['contactAddress1']);
        $this->assertInternalType('string', $event[0]['contactAddress2']);
        $this->assertInternalType('string', $event[0]['contactCity']);
        $this->assertInternalType('string', $event[0]['contactState']);
        $this->assertInternalType('string', $event[0]['contactZip']);
        $this->assertInternalType('string', $event[0]['contactCountry']);
        $this->assertInternalType('string', $event[0]['contactEmail']);

        // Test that we get the expected data
        $this->assertEquals(153529, $event[0]['id']);
        $this->assertEquals('Test Event', $event[0]['title']);
        $this->assertEquals(true, $event[0]['live']);
        $this->assertEquals('Fremont Abbey Arts Center', $event[0]['address1']);
        $this->assertEquals('4272 Fremont Ave North', $event[0]['address2']);
        $this->assertEquals('Seattle', $event[0]['city']);
        $this->assertEquals('CA', $event[0]['state']);
        $this->assertEquals(98103, $event[0]['zip']);

    }


    public function testGetDates()
    {
        $dates = $this->eventInfo->getDates(153529);
        $this->assertCount(12, $dates);

        foreach ($dates as $date) {
            $this->assertArrayHasKey('id', $date);
            $this->assertArrayHasKey('dateStart', $date);
            $this->assertArrayHasKey('dateEnd', $date);
            $this->assertArrayHasKey('timeStart', $date);
            $this->assertArrayHasKey('timeEnd', $date);
            $this->assertArrayHasKey('live', $date);
            $this->assertArrayHasKey('available', $date);

            if ($date['id'] === 647481) {
                $this->assertEquals(false, $date['live']);
            }
        }
    }
}
