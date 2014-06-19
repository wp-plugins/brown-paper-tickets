<?php

namespace BrownPaperTickets\APIv2;

use PHPUnit_Framework_TestCase;

class BrownPaperTicketsCreateEventTest extends \PHPUnit_Framework_TestCase
{


    public function __construct()
    {
        $this->bptApi = new ManageEvent('p9ny29gi5h');
    }

    public function testCreateEvent()
    {
        $bpt = $this->bptApi;

        $eventParams = array(
            'name' => 'Chandler PHP API Wrapper Test - Please Delete',
            'city' => 'Seattle',
            'state' => 'WA',
            'shortDescription' => 'This is a short description.',
            'fullDescription' => 'This is a Full Description. So long.'
        );

        $createEvent = $bpt->createEvent('chandler_api', $eventParams);

    }
}
