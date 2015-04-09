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
        $this->bpt = new ManageCart(getenv('DEVID'));
    }

    public function testGetCartId()
    {
        $this->assertInternalType('null', $this->bpt->getCartID());
        $this->assertInternalType('null', $this->bpt->getCartCreatedAt());

        $initCart = $this->bpt->initCart();

        $this->assertInternalType('string', $this->bpt->getCartID());
        $this->assertInternalType('integer', $this->bpt->getCartCreatedAt());

        $this->assertInternalType('array', $initCart);
        $this->assertArrayHasKey('cartID', $initCart);
        $this->assertArrayHasKey('cartCreatedAt', $initCart);
    }

    public function testSetPricesAndRequirements()
    {
        $prices = array(
            20276327 => array(
                'quantity' => 1,
                'shippingMethod' => 2
            ),
            2327400 => array(
                'quantity' => 3,
                'shippingMethod' => 3
            ),
            3424565 => array(
                'quantity' => 1,
                'shippingMethod' => 5
            ),
        );

        $actualPrices = array(
            20276327 => array(
                'quantity' => 1,
                'shippingMethod' => 2
            ),
            2327400 => array(
                'quantity' => 3,
                'shippingMethod' => 3
            ),
        );

        $this->bpt->setPrices($prices);

        $prices = $this->bpt->getPrices();

        $this->assertEquals($actualPrices, $this->bpt->getPrices());

        $this->assertTrue($this->bpt->getRequireWillCallNames());
    }


    public function testSendPricesAndRequirements()
    {
        $prices = array(
            20276327 => array(
                'quantity' => 1,
                'shippingMethod' => 2
            ),
            3424571 => array(
                'quantity' => 3,
                'shippingMethod' => 3
            )
        );

        $actualPrices = array(
            3424571 => array(
                'quantity' => 3,
                'shippingMethod' => 3
            )
        );

        $this->bpt->setPrices($prices);
        $this->assertEquals($prices, $this->bpt->getPrices());

        $results = $this->bpt->sendPrices();
        $this->assertFalse($results['success']);

        $this->bpt->initCart();
        $results = $this->bpt->sendPrices();

        $this->assertTrue($results['success']);
        $this->assertEquals($actualPrices, $this->bpt->getPrices());
        $this->assertEquals(36, $this->bpt->GetValue());
        $this->assertArrayHasKey(20276327, $this->bpt->getPricesNotAdded());

        $this->assertTrue($this->bpt->getRequireWillCallNames());
        $this->assertTrue($this->bpt->getRequireFullBilling());
    }

    public function testRemovePrices()
    {
        $prices = array(
            3424567 => array(
                'quantity' => 1,
                'shippingMethod' => 2
            ),
            3424571 => array(
                'quantity' => 3,
                'shippingMethod' => 3
            )
        );
        $this->bpt->initCart();
        $this->bpt->setPrices($prices);
        $results = $this->bpt->sendPrices();

        $actualPrices = array(
            3424571 => array(
                'quantity' => 3,
                'shippingMethod' => 3
            )
        );

        $this->assertEquals($actualPrices, $this->bpt->getPrices());

        $this->assertEquals(36, $this->bpt->getValue());
        $this->bpt->removePrices(array(3424571));

        $actualPrices = array(
            3424571 => array(
                'quantity' => 0,
                'shippingMethod' => 3,
            )
        );

        $this->assertEquals($actualPrices, $this->bpt->getPrices());
        $results = $this->bpt->sendPrices();
        $removed = $this->bpt->getPricesRemoved();
        $this->assertEquals(3424571, $removed[0]);
        $this->assertEquals(array(), $this->bpt->getPrices());
        $this->assertEquals(0, $this->bpt->getValue());

        $this->assertEquals(array(), $this->bpt->getPrices());
    }

    public function testSetShipping()
    {
        $prices = array(
            3424567 => array(
                'quantity' => 1,
                'shippingMethod' => 2
            ),
            3424571 => array(
                'quantity' => 3,
                'shippingMethod' => 3
            )
        );

        $this->bpt->initCart();
        $this->bpt->setPrices($prices);
        $this->bpt->sendPrices();

        $incomplete = array(
            'firstName' => 'API',
            'lastName' => 'Test'
        );

        $this->assertFalse($this->bpt->setShipping($incomplete));

        $willCall = array(
            'firstName' => 'API',
            'lastName' => 'Test',
            'address' => '123 Street',
            'city' => 'Seattle',
            'state' => 'WA',
            'zip' => '98122',
            'country' => 'US'
        );
        $this->assertInternalType('array', $this->bpt->setShipping($willCall));

        $willCall['willCallFirstName'] = 'API';
        $willCall['willCallLastName'] = 'Test';

        $this->assertEquals($willCall, $this->bpt->getShipping());

        $willCall['willCallFirstName'] = 'Ticket';
        $willCall['willCallLastName'] = 'Pickup';

        $true = $this->bpt->setShipping($willCall);

        $this->assertTrue($true['success']);
        $this->assertEquals($willCall, $this->bpt->getShipping());
    }

    public function testSendShipping()
    {
        $prices = array(
            3424567 => array(
                'quantity' => 1,
                'shippingMethod' => 2
            ),
            3424571 => array(
                'quantity' => 3,
                'shippingMethod' => 3
            )
        );

        $false = $this->bpt->sendShipping();

        $this->assertFalse($false['success']);
        $this->assertEquals('Invalid cart.', $false['message']);

        $this->bpt->initCart();
        $false = $this->bpt->sendShipping();
        $this->assertFalse($false['success']);
        $this->assertEquals('No tickets.', $false['message']);

        $this->bpt->setPrices($prices);
        $this->bpt->sendPrices();

        $shipping = array(
            'firstName' => 'API',
            'lastName' => 'Test',
            'address' => '123 Street',
            'city' => 'Seattle',
            'state' => 'WA',
            'zip' => '98122',
            'country' => 'US',
            'willCallFirstName' => 'Ticket',
            'willCallLastName' => 'Pickup'
        );

        $this->assertInternalType('array', $this->bpt->setShipping($shipping));
        $this->assertTrue($this->bpt->sendShipping());
    }

    public function testSetBilling()
    {
        $incompleteBilling = array(
            'firstName' => 'API'
        );

        $billing = array(
            'number' => '1234567890000000',
            'expMonth' => 10,
            'expYear' => 2018,
            'cvv2' => 666,
            'firstName' => 'API',
            'lastName' => 'Test',
            'address' => '123 Street',
            'city' => 'Seattle',
            'state' => 'WA',
            'zip' => '98122',
            'country' => 'US',
            'willCallFirstName' => 'Ticket',
            'willCallLastName' => 'Pickup'
        );

        $prices = array(
            3424567 => array(
                'quantity' => 1,
                'shippingMethod' => 2
            ),
            3424571 => array(
                'quantity' => 3,
                'shippingMethod' => 3
            )
        );

        $false = $this->bpt->setBilling($incompleteBilling);
        $this->assertFalse($false['success']);
        $this->assertEquals('First and last name are required.', $false['message']);

        $this->bpt->setPrices($prices);
        $this->bpt->initCart();
        $this->bpt->sendPrices();

        $this->assertTrue($this->bpt->getRequireFullBilling());

        $false = $this->bpt->setBilling($billing);

        $this->assertFalse($false['success']);
        $this->assertEquals('Email and telephone are required.', $false['message']);

        $billing['email'] = 'someone@somewhere.com';
        $billing['phone'] = '800.838.3006';
        $false = $this->bpt->setBilling($billing);

        $this->assertFalse($false['success']);
        $this->assertEquals('Credit card info is required.', $false['message']);

        $billing['type'] = 'BITCOINZZZZ';
        $false = $this->bpt->setBilling($billing);

        $this->assertEquals('Type must be Visa, Mastercard, Discover or Amex.', $false['message']);

        $billing['type'] = 'Visa';
        $true = $this->bpt->setBilling($billing);

        $this->assertEquals('Billing info set.', $true['message']);
        $this->assertTrue($true['success']);

    }

    public function testSendBilling()
    {
        $prices = array(
            3424567 => array(
                'quantity' => 1,
                'shippingMethod' => 2
            ),
            3424571 => array(
                'quantity' => 3,
                'shippingMethod' => 3
            )
        );

        $shipping = array(
            'firstName' => 'API',
            'lastName' => 'Test',
            'address' => '123 Street',
            'city' => 'Seattle',
            'state' => 'WA',
            'zip' => '98122',
            'country' => 'US',
            'willCallFirstName' => 'Ticket',
            'willCallLastName' => 'Pickup'
        );

        $billing = array(
            'type' => 'Visa',
            'number' => '1234567890000000',
            'expMonth' => 10,
            'expYear' => 2018,
            'cvv2' => 666,
            'firstName' => 'API',
            'lastName' => 'Test',
            'address' => '123 Street',
            'city' => 'Seattle',
            'state' => 'WA',
            'zip' => '98122',
            'country' => 'US',
            'email' => 'someone@somewhere.com',
            'phone' => '800.838.3006',
            'willCallFirstName' => 'Ticket',
            'willCallLastName' => 'Pickup'
        );

        $noCart = $this->bpt->sendBilling();
        $this->assertFalse($noCart['success']);
        $this->assertEquals('Invalid cart.', $noCart['message']);

        $this->bpt->initCart();
        $noPrices = $this->bpt->sendBilling();
        $this->assertFalse($noPrices['success']);
        $this->assertEquals('No prices set.', $noPrices['message']);

        $this->bpt->setPrices($prices);
        $noPricesSent = $this->bpt->sendBilling();
        $this->assertFalse($noPricesSent['success']);
        $this->assertEquals('Prices have not been sent.', $noPricesSent['message']);
        $this->bpt->sendPrices();

        $noShipping = $this->bpt->sendBilling();
        $this->assertFalse($noShipping['success']);
        $this->assertEquals('No shipping info set.', $noShipping['message']);
        $this->bpt->setShipping($shipping);

        $noShippingSent = $this->bpt->sendBilling();
        $this->assertFalse($noShippingSent['success']);
        $this->assertEquals('Shipping info has not been sent.', $noShippingSent['message']);
        $this->bpt->sendShipping();

        $noBilling = $this->bpt->sendBilling();
        $this->assertFalse($noBilling['success']);
        $this->assertEquals('No billing info set.', $noBilling['message']);

        $setBilling = $this->bpt->setBilling($billing);

        $success = $this->bpt->sendBilling();

        $this->assertTrue($success['success']);
        $this->assertEquals('Purchase complete.', $success['message']);

        $alreadySent = $this->bpt->sendBilling();
        $this->assertFalse($alreadySent['success']);
        $this->assertEquals('Billing info has already been sent.', $alreadySent['message']);

        $receipt = $this->bpt->getReceipt();
        $this->assertInternalType('array', $receipt);
        $this->assertArrayNotHasKey('success', $receipt);
        $this->assertArrayNotHasKey('message', $receipt);
        $this->assertArrayHasKey('ticketURL', $receipt);
        $this->assertArrayHasKey('receiptURL', $receipt);
        $this->assertArrayHasKey('total', $receipt);
        $this->assertArrayHasKey('cartID', $receipt);
    }

    public function testPassCartID()
    {
        $fail = $this->bpt->initCart('z6UDpmZQbAzzrQRk6f5wzi4TH', 1422474020);

        $this->assertInternalType('array', $fail);
        $this->assertFalse($fail['success']);
        $this->assertEquals('Cart has expired.', $fail['message']);
    }
}
