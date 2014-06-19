<?php

namespace BrownPaperTickets\APIv2;

use PHPUnit_Framework_TestCase;

class BrownPaperTicketsSubmitOrderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test that true does in fact equal true
     */

    protected $bpt;
    protected $cartID;

    public function __construct()
    {
        $this->bpt = new ManageCart('p9ny29gi5h');
        $this->cartID = $this->bpt->getCartID();
    }

    public function testGetCartID()
    {
        $this->assertInternalType('string', $this->cartID);
    }

    public function testPurchaseTickets()
    {
        $prices = array(
            20276327 => array(
                'quantity' => 1,
                'shippingMethod' => 2
            ),
            2327400 => array(
                'quantity' => 3,
                'shippingMethod' => 3
            )
        );

        $params = array(
            'cartID' => $this->cartID,
            'prices' => $prices,
        );

        $addPrices = $this->bpt->addPricestoCart($params);

        // Test that the Cart ID is a value of $addPrices
        // and has this instance's cart ID set.
        $this->assertArrayHasKey('cartID', $addPrices);
        $this->assertContains($this->cartID, $addPrices['cartID']);

        // Test that the pricesAdded array has one element.
        $this->assertArrayHasKey('pricesAdded', $addPrices);
        $this->assertCount(1, $addPrices['pricesAdded']);
        $pricesAdded = $addPrices['pricesAdded'][0];

        // Test that result key exists and hasa value of 'success'
        $this->assertArrayHasKey('result', $pricesAdded);
        $this->assertEquals('success', $pricesAdded['result']);

        // Test that Ticket 2327400 has been added.
        $this->assertArrayHasKey('priceID', $pricesAdded);
        $this->assertEquals('2327400', $pricesAdded['priceID']);

        // Test that the status key exists and is set to 'Price has
        // been added'.
        $this->assertArrayHasKey('status', $pricesAdded);
        $this->assertEquals('Price has been added.', $pricesAdded['status']);


        // Test that some prices failed.
        $this->assertArrayHasKey('pricesNotAdded', $addPrices);
        $this->assertCount(1, $addPrices['pricesNotAdded']);
        $pricesNotAdded = $addPrices['pricesNotAdded'][0];

        $this->assertArrayHasKey('result', $pricesNotAdded);
        $this->assertEquals('fail', $pricesNotAdded['result']);

        $this->assertArrayHasKey('status', $pricesNotAdded);
        $this->assertEquals('no such price', $pricesNotAdded['status']);

        $this->assertArrayHasKey('priceID', $pricesNotAdded);
        $this->assertEquals('20276327', $pricesNotAdded['priceID']);


        // Test that the cartValue element exists and it set to '0.00'
        $this->assertArrayHasKey('cartValue', $addPrices);
        $this->assertEquals('0.00', $addPrices['cartValue']);

        $params = array(
            'cartID' => $this->cartID,
            'shippingFirstName' => 'Chandler',
            'shippingLastName' => 'Blum',
            'shippingAddress' => '124 PHP',
            'shippingCity' => 'Seattle',
            'shippingState' => 'WA',
            'shippingZip' => '98107',
            'shippingCountry' => 'US'
        );

        $shippingInfo = $this->bpt->addShippingInfoToCart($params);

        $this->assertArrayHasKey('result', $shippingInfo);
        $this->assertArrayHasKey('message', $shippingInfo);
        $this->assertArrayHasKey('cartID', $shippingInfo);

        $this->assertEquals('success', $shippingInfo['result']);
        $this->assertEquals('Shipping method has been added.', $shippingInfo['message']);
        $this->assertContains($this->cartID, $shippingInfo['cartID']);

        $params = array(
            'cartID' => $this->cartID,
            'ccType' => 'Visa',
            'ccNumber' => 1234567890000000,
            'ccExpMonth' => 10,
            'ccExpYear' => 2016,
            'ccCvv2' => 123,
            'billingFirstName' => 'Chandler',
            'billingLastName' => 'Blum',
            'billingAddress' => '5810 8th Ave NW',
            'billingCity' => 'Seattle',
            'billingState' => 'WA',
            'billingZip' => 98107,
            'billingCountry' => 'United States',
            'email' => 'chandlerblum@gmail.com',
            'phone' => 9784176259
        );

        $billingInfo = $this->bpt->addBillingInfoToCart($params);

        $this->assertArrayHasKey('result', $billingInfo);
        $this->assertEquals('success', $billingInfo['result']);
        $this->assertArrayHasKey('message', $billingInfo);
        $this->assertEquals('Purchase complete.', $billingInfo['message']);
        $this->assertArrayHasKey('cartID', $billingInfo);

    }

    // public function testSubmitOrderStage3()
    // {
    //     $bpt = $this->bptAPI;

    //     $orderParams = array(
    //         'cartID' => $this->cartID,
    //         ''
    //     );
    // }
}
