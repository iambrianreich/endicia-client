<?php

namespace Tests\RWC\Endicia;

use PHPUnit\Framework\TestCase;
use RWC\Endicia\Address;
use RWC\Endicia\CertifiedIntermediary;
use RWC\Endicia\InvalidArgumentException;
use RWC\Endicia\LabelRequest;

class LabelRequestTest extends TestCase
{
	private $requesterId = '1234';
	
	/**
	 *	A Double test to cover application level dependancy
	 */
	public function testAddress(): void
    {
        $this->assertInstanceOf(
            Address::class,
            new Address('John Doe', NULL, '123 Main St.', NULL, NULL, NULL, 'Nowhere', 'NM', '80000', NULL, 'US', NULL, NULL)
        );
    }
	
	/**
	 *	@depends testAddress
	 */
	public function testSimpleDomesticLabel() : void
	{
		$ci = CertifiedIntermediary::createFromToken('abcdef');
		$to = new Address('Alice Reynolds', NULL, '123 Maple St.', 'Apt. 3C', NULL, NULL, 'Smalltown', 'PA', '17000', NULL, 'US', NULL, 'alice@smalltown.pa.us');
		$from = new Address('Bob Smith', 'Initech', '123 Market St.', NULL, NULL, NULL, 'Big City', 'NY', '09000', NULL, 'US', '8005551234', NULL);
		
		$this->assertInstanceOf(
			LabelRequest::class,
			new LabelRequest($this->requesterId, $ci, LabelRequest::MAIL_CLASS_FIRST, 0.2, $from, $to)
		);
	}
}
