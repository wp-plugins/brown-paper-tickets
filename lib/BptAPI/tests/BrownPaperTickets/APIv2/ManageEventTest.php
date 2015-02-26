<?php

namespace BrownPaperTickets\APIv2;

use PHPUnit_Framework_TestCase;

class BrownPaperTicketsCreateEventTest extends \PHPUnit_Framework_TestCase
{

    public $bpt = null;

    public function __construct()
    {
        $this->bpt = new ManageEvent(getenv('DEVID'));
        $this->bpt->setOption('logErrors', true);
    }

    // public function testCreateEvent()
    // {
    //     $this->bpt = $this->bpt;

    //     $eventParams = array(
    //         'username' => 'chandler_api',
    //         'name' => 'Chandler PHP API Wrapper Test - Please Delete',
    //         'city' => 'Seattle',
    //         'state' => 'WA',
    //         'shortDescription' => 'This is a short description.',
    //         'fullDescription' => 'This is a Full Description. So long.'
    //     );

    //     $createEvent = $this->bpt->createEvent($eventParams);
    //     $this->assertInternalType('integer', $createEvent);

    //     $dateParams = array(
    //         'username' => 'chandler_api',
    //     );
    // }

     /**
     * @expectedException        InvalidArgumentException
     */
    public function testChangeEventNoParams()
    {
        $params = array();
        $this->bpt->createEvent($params);
        $this->bpt->changeEvent($params);

    }


    /**
     * @expectedException        InvalidArgumentException
     */
    public function testChangeEventInvalidArgumentUsername()
    {

        $this->bpt->changeEvent(null, null);
    }

    /**
     * @expectedException        InvalidArgumentException
     */
    public function testChangeEventInvalidArgumentEventParams()
    {
        $this->bpt->changeEvent(null);
    }

    /**
     * @expectedException        InvalidArgumentException
     */
    public function testChangeEventInvalidArgumentEventID()
    {

        $eventParams = array(
            'name' => 'Test Event'
        );

        $this->bpt->changeEvent($eventParams);
    }

    public function testChangeEvent()
    {
        $this->bpt = $this->bpt;

        $originalEventParams = array(
            'username' => 'chandler_api',
            'eventID' => 445143,
            'name' => 'Another Test Event!',
            'city' => 'Seattle',
            'state' => 'WA',
            'shortDescription' => 'Unicorn Origami',
            'fullDescription' => 'I\'ve... seen things you people wouldn\'t believe... [laughs] Attack ships on fire off the shoulder of Orion. I watched c-beams glitter in the dark near the Tannhäuser Gate. All those... moments... will be lost in time, like [coughs] tears... in... rain. Time... to die...
<img width="100%" src="http://upload.wikimedia.org/wikipedia/en/1/1f/Tears_In_Rain.png" />',
            'address1' => 'Brown Paper Tickets',
            'address2' => '220 Nickerson St',
            'zip' => 98102,
            'phone' => '1.800.838.3006',
            'web' => 'http://www.brownpapertickets.com',
            'endOfEventMessage' => 'Some message at the end of event',
            'endOfSaleMessage' => 'Some end of sale message.',
            'dateNotes' => 'Date Notes',
            'notes' => 'Notes for the event',
            'keywords' => 'Test, API',
            'contactName' => 'Chandler',
            'contactEmail' => 'chandler@brownpapertickets.com',
            'contactPhone' => '1.800.838.3006',
            'contactFax' => '',
            'contactAddress1' => '220 Nickerson Street',
            'contactAddress2' => '',
            'contactCity' => 'Seattle',
            'contactState' => 'WA',
            'contactZip' => 98107,
            'contactCountry' => 'United States'
        );

        $newEventParams = array(
            'username' => 'chandler_api',
            'eventID' => 445143,
            'name' => 'API Test Event',
            'shortDescription' => 'A New Event Description!',
            'fullDescription' => 'Changing the event description!',
            'address1' => 'TREEHOUSE',
            'address2' => 'Across from 711',
            'zip' => 98107,
            'phone' => '1-800-838-3006',
            'web' => 'http://www.brownpapertickets.com/event/153529',
            'endOfEventMessage' => 'Some NEW message at the end of event',
            'endOfSaleMessage' => 'Some NEW end of sale message.',
            'dateNotes' => 'NEW Date Notes',
            'notes' => 'NEW Notes for the event',
            'keywords' => 'NEW, Test, API',
            'contactName' => 'New Chandler',
            'contactEmail' => 'chandler@brownpapertickets.com',
            'contactPhone' => '1-800-838-3006',
            'contactFax' => 'Fax No',
            'contactAddress1' => 'TREEHOUSE',
            'contactAddress2' => '',
            'contactCity' => 'Boston',
            'contactState' => 'MA',
            'contactZip' =>  01950,
            'contactCountry' => 'US',
            //'activated' => 'f'
        );

        $this->assertTrue($this->bpt->changeEvent($newEventParams));

        $this->assertTrue($this->bpt->changeEvent($originalEventParams));


    }
}
