<?php

/**
 * Unit test sample for PHPUnit
 *
 * @covers FreeGeoIp
 */

class FreeGeoIpTest extends PHPUnit_Framework_TestCase{

    private $subject = null;

    public function FreeGeoIpTest(){

        $this->subject = new FreeGeoIp();

    }

    public function testHasTag(){

        $this->assertNotEmpty( $this->subject->getTag() );

    }

    public function testHasUrl(){

        $this->assertNotEmpty( $this->subject->getUrl() );

    }

}