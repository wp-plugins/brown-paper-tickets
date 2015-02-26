<?php

namespace BrownPaperTickets\APIv2;

//use BrownPaperTickets\APIv2\eventInfo;
use PHPUnit_Framework_TestCase;

class BrownPaperTicketsGetSalesInfoTest extends \PHPUnit_Framework_TestCase
{
    public function __construct()
    {
        $this->salesInfo = new SalesInfo(getenv('DEVID'));
    }

    public function testGetEventSales()
    {
        $sales = $this->salesInfo->getEventSales('chandler_api', 443322);

        $this->assertCount(4, $sales);

        foreach ($sales as $sale) {
            $this->assertArrayHasKey('title', $sale);
            $this->assertArrayHasKey('link', $sale);
            $this->assertArrayHasKey('id', $sale);
            $this->assertArrayHasKey('eventStatus', $sale);
            $this->assertArrayHasKey('ticketsSold', $sale);
            $this->assertArrayHasKey('collectedValue', $sale);
            $this->assertArrayHasKey('paidValue', $sale);

            $this->assertInternalType('string', $sale['title']);
            $this->assertInternalType('string', $sale['link']);
            $this->assertInternalType('integer', $sale['id']);
            $this->assertInternalType('string', $sale['eventStatus']);
            $this->assertInternalType('integer', $sale['ticketsSold']);
            $this->assertInternalType('integer', $sale['collectedValue']);
            $this->assertInternalType('integer', $sale['paidValue']);
        }
    }

    public function testGetDateSales()
    {
        $sales = $this->salesInfo->getDateSales('chandler_api', 443322);

        $this->assertCount(5, $sales);

        foreach ($sales as $sale) {
            $this->assertArrayHasKey('id', $sale);
            $this->assertArrayHasKey('ticketsSold', $sale);
            $this->assertArrayHasKey('collectedValue', $sale);
            $this->assertArrayHasKey('beginTime', $sale);
            $this->assertArrayHasKey('endTime', $sale);
            $this->assertArrayHasKey('prices', $sale);

            $this->assertInternalType('integer', $sale['id']);
            $this->assertInternalType('integer', $sale['ticketsSold']);
            $this->assertInternalType('integer', $sale['collectedValue']);
            $this->assertInternalType('string', $sale['beginTime']);
            $this->assertInternalType('string', $sale['endTime']);
            $this->assertInternalType('array', $sale['prices']);
        }
    }

    public function testEventOrders()
    {
        $orders = $this->salesInfo->getOrders('chandler_api', 443322);

        $this->assertCount(33, $orders);

        foreach ($orders as $order) {

            $this->assertArrayHasKey('time', $order);
            $this->assertArrayHasKey('dateID', $order);
            $this->assertArrayHasKey('priceID', $order);
            $this->assertArrayHasKey('quantity', $order);
            $this->assertArrayHasKey('firstName', $order);
            $this->assertArrayHasKey('lastName', $order);
            $this->assertArrayHasKey('address', $order);
            $this->assertArrayHasKey('city', $order);
            $this->assertArrayHasKey('state', $order);
            $this->assertArrayHasKey('zip', $order);
            $this->assertArrayHasKey('country', $order);
            $this->assertArrayHasKey('email', $order);
            $this->assertArrayHasKey('phone', $order);
            $this->assertArrayHasKey('creditCard', $order);
            $this->assertArrayHasKey('shippingMethod', $order);
            $this->assertArrayHasKey('notes', $order);
            $this->assertArrayHasKey('ticketNumber', $order);
            $this->assertArrayHasKey('section', $order);
            $this->assertArrayHasKey('row', $order);
            $this->assertArrayHasKey('seat', $order);

            $this->assertInternalType('string', $order['time']);
            $this->assertInternalType('integer', $order['dateID']);
            $this->assertInternalType('integer', $order['priceID']);
            $this->assertInternalType('integer', $order['quantity']);
            $this->assertInternalType('string', $order['firstName']);
            $this->assertInternalType('string', $order['lastName']);
            $this->assertInternalType('string', $order['address']);
            $this->assertInternalType('string', $order['city']);
            $this->assertInternalType('string', $order['state']);
            $this->assertInternalType('string', $order['zip']);
            $this->assertInternalType('string', $order['country']);
            $this->assertInternalType('string', $order['email']);
            $this->assertInternalType('string', $order['phone']);
            $this->assertInternalType('integer', $order['creditCard']);
            $this->assertInternalType('string', $order['notes']);
            $this->assertInternalType('string', $order['ticketNumber']);
            $this->assertInternalType('string', $order['section']);
            $this->assertInternalType('string', $order['row']);
            $this->assertInternalType('string', $order['seat']);
        }
    }
}
