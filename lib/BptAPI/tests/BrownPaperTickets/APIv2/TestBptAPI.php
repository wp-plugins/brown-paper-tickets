<?php

namespace BrownPaperTickets\APIv2;

use PHPUnit_Framework_TestCase;

class BrownPaperTicketsClassTest extends \PHPUnit_Framework_TestCase
{
    public $bpt = null;

    public function __construct()
    {
        $this->bpt = new BptAPI('notneeded', array('logErrors' => true));
    }

    // public function testCheckDateFormat()
    // {
    //     $badDate = '1-14-1986 7:24';

    //     $goodDate = 'JAN-14-1986 07:00';

    //     $expectFalse = $this->bpt->checkDateFormat($badDate);

    //     $expectTrue = $this->bpt->checkDateFormat($goodDate);

    //     $this->assertInternalType('boolean', $expectFalse);

    // }
    public function testSetOption()
    {
        $setOption = $this->bpt->setOption('logErrors', true);

        $logErrors = $this->bpt->getOption('logErrors');

        $this->assertTrue($setOption);
        $this->assertTrue($logErrors);
    }

    /**
     * @expectedException Exception
     */
    public function testSetOptionException()
    {
        $this->bpt->getOption('something');
    }
    
    public function testErrors()
    {
        $this->bpt->setError('someMethod', 'Some Error');
        $this->bpt->setError('anotherMethod', 'WHAT IS HAPPENING');
        $this->bpt->setError('methodMan', 'ANOTHER ERROR?');

        $newest = $this->bpt->getErrors('newest');

        $this->assertArrayHasKey('methodMan', $newest);

        $allErrors = $this->bpt->getErrors();

        $this->assertCount(3, $allErrors);
    }
}
