<?php

namespace BrownPaperTickets\APIv2;

use PHPUnit_Framework_TestCase;

class BrownPaperTicketsGetEventImagesTest extends \PHPUnit_Framework_TestCase
{
    public function __construct()
    {
        $this->eventInfo = new EventInfo(getenv('DEVID'));
    }

    public function testGetImages()
    {

        $this->eventInfo->setOption('logErrors', true);

        $eventImages = $this->eventInfo->getImages(153529);

        $this->assertCount(3, $eventImages);

        $this->assertArrayHasKey('large', $eventImages[0]);
        $this->assertArrayHasKey('medium', $eventImages[0]);
        $this->assertArrayHasKey('small', $eventImages[0]);

        $this->assertArrayHasKey('large', $eventImages[1]);
        $this->assertArrayHasKey('medium', $eventImages[1]);
        $this->assertArrayHasKey('small', $eventImages[1]);

        $this->assertArrayHasKey('large', $eventImages[2]);
        $this->assertArrayHasKey('medium', $eventImages[2]);
        $this->assertArrayHasKey('small', $eventImages[2]);


        $this->assertEquals('http://www.brownpapertickets.com/g/e/64100-250.gif', $eventImages[0]['large']);
        $this->assertEquals('http://www.brownpapertickets.com/g/e/64100-100.gif', $eventImages[0]['medium']);
        $this->assertEquals('http://www.brownpapertickets.com/g/e/64100-50.gif', $eventImages[0]['small']);

        $this->assertEquals('http://www.brownpapertickets.com/g/e/54148-250.gif', $eventImages[1]['large']);
        $this->assertEquals('http://www.brownpapertickets.com/g/e/54148-100.gif', $eventImages[1]['medium']);
        $this->assertEquals('http://www.brownpapertickets.com/g/e/54148-50.gif', $eventImages[1]['small']);

        $this->assertEquals('http://www.brownpapertickets.com/g/e/394622-250.gif', $eventImages[2]['large']);
        $this->assertEquals('http://www.brownpapertickets.com/g/e/394622-100.gif', $eventImages[2]['medium']);
        $this->assertEquals('http://www.brownpapertickets.com/g/e/394622-50.gif', $eventImages[2]['small']);

        $noImages = $this->eventInfo->getImages(900435);
        $this->assertNull($noImages);

        $error = $this->eventInfo->getErrors('newest');
        $this->assertArrayHasKey('getImages', $error);
        $this->assertEquals('No images found.', $error['getImages']);

        $invalidEvent = $this->eventInfo->getImages(153512);
        $this->assertFalse($invalidEvent);

        $error = $this->eventInfo->getErrors('newest');
        $this->assertArrayHasKey('getImages', $error);
        $this->assertEquals('The specified event could not be found.', $error['getImages']);
    }
}
