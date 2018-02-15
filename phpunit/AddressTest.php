<?php

namespace Tests\RWC\Endicia;

use PHPUnit\Framework\TestCase;
use RWC\Endicia\Address;

class AddressTest extends TestCase
{
	public function testCanBeCreatedFromValidData() : void
    {
        $this->assertInstanceOf(
            Address::class,
            new Address('John Doe', NULL, '123 Main St.', NULL, 'Nowhere', 'NM', '00000', NULL, 'US')
        );
    }
	
	/**
	 * @expectedException RWC\Endicia\InvalidArgumentException
	 * @expectedExceptionMessage The company must be provided unless name is provided
	 */
	public function testNameAndCompanyCannotBothBeNull() : void
	{
		$address = new Address(NULL, NULL, '123 Main St.', NULL, 'Nowhere', 'NM', '00000', NULL, 'US');
	}
	
	/**
	 * @expectedException RWC\Endicia\InvalidArgumentException
	 * @expectedExceptionMessage The name must not be longer than 47 characters
	 */
	public function testNameMustNotBeMoreThan47Characters() : void
	{
		$address = new Address(
			'123456789012345678901234567890123456789012345678',
			NULL, '123 Main St.', NULL, 'Nowhere', 'NM', '00000', NULL, 'US');
	}
	
	public function testCanUseAliasesForUS() : void
    {
        $address = new Address('John Doe', NULL, '123 Main St.', NULL, 'Nowhere', 'NM', '00000', NULL, 'USA');
        $this->assertInstanceOf(Address::class, $address);
		$this->assertEquals($address->getCountry(), 'US');
		
        $address = new Address('John Doe', NULL, '123 Main St.', NULL, 'Nowhere', 'NM', '00000', NULL, 'United States');
        $this->assertInstanceOf(Address::class, $address);
		$this->assertEquals($address->getCountry(), 'US');
		
		$address = new Address('John Doe', NULL, '123 Main St.', NULL, 'Nowhere', 'NM', '00000', NULL, 'United States of America');
        $this->assertInstanceOf(Address::class, $address);
		$this->assertEquals($address->getCountry(), 'US');
    }
	
	/**
	 * @expectedException RWC\Endicia\InvalidArgumentException
	 * @expectedExceptionMessage The country must be an ISO 3166 two
	 *		letter country code, a country short name, or a common
	 *		vulgar country name e.g. USA for United States
	 */
	public function testCountryMustBeRecognized() : void
	{
		$address = new Address('Jane Doe', NULL, '123 Main St.', NULL, 'Nowhere', 'NM', '00000', NULL, 'XX');
	}
}
