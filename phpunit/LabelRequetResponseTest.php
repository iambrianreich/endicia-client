<?php

namespace Tests\RWC\Endicia;

use PHPUnit\Framework\TestCase;
use RWC\Endicia\LabelRequestResponse;

class LabelRequestResponseTest extends TestCase
{	
	/**
	 *	
	 */
	public function testReadSimpleLabelRequestResponse() : void
	{	
		$xml = file_get_contents('phpunit/data/SimpleLabelRequestResponse.xml');
		
		$this->assertInstanceOf(
			LabelRequestResponse::class,
			LabelRequestResponse::fromXml($xml)
		);
	}
}
