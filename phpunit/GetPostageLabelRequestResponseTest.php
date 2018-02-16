<?php

namespace Tests\RWC\Endicia;

use PHPUnit\Framework\TestCase;
use RWC\Endicia\GetPostageLabelRequestResponse;

class GetPostageLabelRequestResponseTest extends TestCase
{	
	/**
	 *	
	 */
	public function testReadSimpleLabelRequestResponse() : void
	{	
		$xml = file_get_contents(__DIR__ . '/data/GetPostageLabelRequestResponse/SimpleGetPostageLabelRequestResponse.xml');
		
		$this->assertInstanceOf(
			GetPostageLabelRequestResponse::class,
			GetPostageLabelRequestResponse::fromXml($xml)
		);
	}
}
