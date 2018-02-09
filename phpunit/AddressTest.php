<?php

namespace Tests\RWC\Endicia;

use PHPUnit\Framework\TestCase;
use RWC\Endicia\Address;

class AddressTest extends TestCase
{
	public function testCanBeCreatedFromValidData(): void
    {
        $this->assertInstanceOf(
            Address::class,
            new Address('John Doe', NULL, '123 Main St.', NULL, NULL, NULL, 'Nowhere', 'NM', '80000', NULL, 'US', NULL, NULL)
        );
    }
}
