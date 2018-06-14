#Endicia PHP Client
The Endicia PHP Client is meant to make it easy for developers to integrate
support for Endicia into their new and existing PHP projects.

##Legal Disclaimer
Endicia PHP Client is a project of Reich Web Consulting and not an
officially support product of Endicia or the USPS

##Requirements
PHP 7.1+
Guzzle 6+ PHP HTTP Client

##Installation
We expect to recommend you install Endicia PHP Client with Composer. Composer
is a dependency management tool for PHP that allows you to declare the
dependencies your project needs and installs them into your project. However,
we have not yet reached that point.

##Usage
The Client makes use of Composer's autoload generator. Therefore if you are
using Composer you should add:

```require_once('vendor/autoload.php');```

to your source file. Now create an instance of the client, use
RWC\Endicia\Client::MODE_SANDBOX for mode while testing.

```
	use RWC\Endicia\Client;

...

	// use the Endicia Testing Sandbox
	$client = new Client(Client::MODE_SANDBOX);

	// run in production mode
	$client = new Client();
```

Endicia allows you to use either a your AccountID and Passphrase or a Token
to authenticate with their service. Load your values into a
RWC\Endicia\CertifiedIntermediary instance to pass along with requests.

```
	use RWC\Endicia\CertifiedIntermediary;

...

	// use AccountId and Passphrase
	$ci = CertifiedIntermediary::createFromCredentials('YOUR_ACCOUNT_ID_', 'YOUR_PASSPHRASE');

	//use a Token
	$ci = CertifiedIntermediary::createFromToken('YOUR_TOKEN_VALUE');
```

You are now ready to make a request to the API. In general you will create a
request, invoke it by passing it as a parameter to the correct method on Client
and receive a AbstractResponse type object as the return value from that method
call. If the response is successful you can retrieve information from the
response via the subclasses accessor methods.

The "Get Your First Postage Label" example from the Endicia documentation
would look like:

```
	use RWC\Endicia\Address;
	use RWC\Endicia\CertifiedIntermediary;
	use RWC\Endicia\Client;
	use RWC\Endicia\LabelRequest;
	use RWC\Endicia\MailClass;

	// use Sandbox for testing
	$client = new Client(Client::MODE_SANDBOX);
	
	$ci = CertifiedIntermediary::createFromToken('YOUR_TOKEN_VALUE');
	
	$to = new Address('Jane Doe', NULL, '1 Hacker Way', NULL, 'Palo Alto', 'CA', '94025', NULL, 'US');
	$from = new Address('John Doe', 'Endicia, Inc.', '278 Castro Street', NULL, 'Mountain View', 'CA', '94041', NULL, 'US');

	$request = new GetPostageLabelRequest('lxxx', $ci, MailClass::PRIORITY, 16, $from, $to);
	$response = $client->getPostageLabel($request);
	if ( $response->isSuccessful() ) {
		// Label in $response->getLabel()
		echo 'Tracking Number: ' . $response->getTrackingNumber();
	} else {
		echo 'Error: ' . $response->getErrorMessage();
	}

```