<?php

namespace BrownPaperTickets\APIv2;

use PHPUnit_Framework_TestCase;

class BrownPaperTicketsGetCartContentsTest extends \PHPUnit_Framework_TestCase
{

    protected $bpt;
    protected $cartID;

    public function __construct()
    {
        $this->bpt = new CartInfo(getenv('DEVID'));

    }

    public function testGetCartContents()
    {
        $cartContents = $this->bpt->getContents('Wtpyn4eyrug7NcCIeE2mvaUqU');

        // Test that the array has the proper keys returned for each ticket.
        foreach ($cartContents as $ticket) {
            $this->assertArrayHasKey('eventID', $ticket);
            $this->assertArrayHasKey('eventName', $ticket);
            $this->assertArrayHasKey('dateID', $ticket);
            $this->assertArrayHasKey('beginTime', $ticket);
            $this->assertArrayHasKey('endTime', $ticket);
            $this->assertArrayHasKey('priceID', $ticket);
            $this->assertArrayHasKey('priceName', $ticket);
            $this->assertArrayHasKey('priceValue', $ticket);
            $this->assertArrayHasKey('shippingMethod', $ticket);
            $this->assertArrayHasKey('seatingSection', $ticket);
            $this->assertArrayHasKey('seatingRow', $ticket);
            $this->assertArrayHasKey('seatingSeat', $ticket);
            $this->assertArrayHasKey('willCallFirstName', $ticket);
            $this->assertArrayHasKey('willCallLastName', $ticket);
        }
    }

    public function testGetCartValue()
    {
        $cartValue = $this->bpt->getValue('Wtpyn4eyrug7NcCIeE2mvaUqU');

        $this->assertArrayHasKey('cartValue', $cartValue);
        $this->assertArrayHasKey('cartCurrency', $cartValue);
        $this->assertArrayHasKey('shippingNeeded', $cartValue);

        $this->assertEquals('0.00', $cartValue['cartValue']);
        $this->assertEquals('USD', $cartValue['cartCurrency']);
        $this->assertEquals(false, $cartValue['shippingNeeded']);
    }
}
